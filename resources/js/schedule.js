const PT_LOCALE = 'pt-PT';

function hashString(input) {
	if (!input) {
		return 0;
	}

	let hash = 0;
	for (let i = 0; i < input.length; i += 1) {
		hash = (hash << 5) - hash + input.charCodeAt(i);
		hash |= 0; // force 32-bit
	}

	return Math.abs(hash);
}

function computeColors(seed) {
	const hue = hashString(seed) % 360;
	const accentHue = (hue + 25) % 360;

	return {
		bg: `hsla(${hue}, 65%, 22%, 0.85)`,
		bgAccent: `hsla(${accentHue}, 70%, 32%, 0.9)`,
		border: `hsla(${hue}, 80%, 65%, 0.85)`,
		text: '#e2e8f0',
	};
}

function formatTimeRange(startTime, endTime) {
	if (startTime && endTime) {
		return `${startTime} - ${endTime}`;
	}
	if (startTime) {
		return startTime;
	}
	if (endTime) {
		return `Termina às ${endTime}`;
	}
	return 'Horário por definir';
}

function cloneDate(date) {
	return new Date(date.getTime());
}

function formatDateKey(date) {
	const year = date.getFullYear();
	const month = String(date.getMonth() + 1).padStart(2, '0');
	const day = String(date.getDate()).padStart(2, '0');
	return `${year}-${month}-${day}`;
}

function startOfWeek(date) {
	const result = cloneDate(date);
	const day = result.getDay(); // 0 (Sunday) - 6 (Saturday)
	const diff = day === 0 ? -6 : 1 - day; // make Monday the first day
	result.setDate(result.getDate() + diff);
	result.setHours(0, 0, 0, 0);
	return result;
}

function weekKeyFromDate(date) {
	return formatDateKey(startOfWeek(date));
}

function weekKeyFromIso(iso) {
	if (!iso) {
		return null;
	}
	const date = new Date(`${iso}T00:00:00`);
	if (Number.isNaN(date.getTime())) {
		return null;
	}
	return weekKeyFromDate(date);
}

function formatWithIntl(date, options, fallback = '') {
	try {
		const formatter = new Intl.DateTimeFormat(PT_LOCALE, options);
		const formatted = formatter.format(date);
		return formatted.replace(/\.$/, '');
	} catch (_e) {
		return fallback;
	}
}

function formatDayLabel(date, style = 'long') {
	return formatWithIntl(date, { weekday: style }, date.toDateString());
}

function formatMonthLabel(date) {
	return formatWithIntl(date, { month: 'short' }, '');
}

function formatDateRangeLabel(date) {
	return formatWithIntl(date, { day: '2-digit', month: 'short' }, formatDateKey(date));
}

function capitalizeFirst(value) {
	if (!value) {
		return value;
	}
	return value.charAt(0).toUpperCase() + value.slice(1);
}

function minutesBetween(start, end) {
	const diff = (end.getTime() - start.getTime()) / 60000;
	return Math.max(1, Math.round(diff));
}

function parseDurationMinutes(event, startDate, endDate) {
	if (typeof event.durationMinutes === 'number') {
		return Math.max(1, Math.round(event.durationMinutes));
	}
	if (startDate && endDate) {
		return minutesBetween(startDate, endDate);
	}
	return null;
}

export default function registerScheduleComponent() {
	if (window.schedulePageComponent) {
		return;
	}

	window.schedulePageComponent = function schedulePageComponent(config = {}) {
		const initial = config.schedule ?? {};
		const dataUrl = config.dataUrl ?? null;

		return {
			academicTerm: initial.academicTerm ?? null,
			rawEvents: Array.isArray(initial.events) ? initial.events : [],
			enhancedEvents: [],
			eventsByWeek: {},
			weekKeys: [],
			currentWeekKey: null,
			selectedCourse: 'all',
			courses: [],
			loading: false,
			error: null,
			dataUrl,
			hasOngoingClass: false,

			init() {
				this.prepare();
				this.goToCurrentWeek();
			},

			prepare() {
				const now = new Date();
				this.enhancedEvents = this.rawEvents
					.map((evt) => this.decorateEvent(evt, now))
					.filter(Boolean)
					.sort((a, b) => a.startDate.getTime() - b.startDate.getTime());

				this.hasOngoingClass = this.enhancedEvents.some((evt) => evt.isOngoing);

				this.buildCourses();
				if (this.selectedCourse !== 'all' && !this.courses.some((c) => c.id === this.selectedCourse)) {
					this.selectedCourse = 'all';
				}
				this.buildWeeks();
			},

			decorateEvent(evt, now = new Date()) {
				if (!evt || !evt.startIso) {
					return null;
				}

				const startDate = new Date(evt.startIso);
				if (Number.isNaN(startDate.getTime())) {
					return null;
				}

				const endDate = evt.endIso ? new Date(evt.endIso) : null;
				const dayIso = evt.dayIso ?? formatDateKey(startDate);
				const weekKey = weekKeyFromIso(dayIso);
				const colors = computeColors(evt.course?.acronym ?? '');
				const duration = parseDurationMinutes(evt, startDate, endDate);
				const isOngoing = Boolean(evt.isOngoing);
				const hasEnded = endDate
					? endDate.getTime() < now.getTime()
					: startDate.getTime() < now.getTime() && !isOngoing;

				return {
					...evt,
					startDate,
					endDate,
					dayIso,
					weekKey,
					timeRange: formatTimeRange(evt.startTime, evt.endTime),
					colors,
					durationMinutes: duration,
					isPast: hasEnded,
					isToday: dayIso === formatDateKey(now),
				};
			},

			buildCourses() {
				const map = new Map();
				this.enhancedEvents.forEach((evt) => {
					const course = evt.course ?? null;
					if (!course || !course.id) {
						return;
					}
					if (map.has(course.id)) {
						return;
					}
					const label = course.acronym
						? `${course.acronym} · ${course.name}`
						: course.name || course.id;
					map.set(course.id, {
						id: course.id,
						label,
						acronym: course.acronym,
						name: course.name,
					});
				});
				this.courses = Array.from(map.values()).sort((a, b) => a.label.localeCompare(b.label, PT_LOCALE));
			},

			buildWeeks() {
				const map = {};
				this.enhancedEvents.forEach((evt) => {
					if (!evt.weekKey) {
						return;
					}
					if (!map[evt.weekKey]) {
						map[evt.weekKey] = [];
					}
					map[evt.weekKey].push(evt);
				});

				Object.keys(map).forEach((key) => {
					map[key].sort((a, b) => a.startDate.getTime() - b.startDate.getTime());
				});

				this.eventsByWeek = map;
				this.weekKeys = Object.keys(map).sort();
			},

			goToCurrentWeek() {
				if (this.weekKeys.length === 0) {
					this.currentWeekKey = null;
					return;
				}

				const todayKey = weekKeyFromDate(new Date());
				if (this.eventsByWeek[todayKey]) {
					this.currentWeekKey = todayKey;
					return;
				}

				const todayStart = new Date(`${todayKey}T00:00:00`);
				const candidate = this.weekKeys.find((key) => {
					const keyDate = new Date(`${key}T00:00:00`);
					return keyDate.getTime() >= todayStart.getTime();
				});

				this.currentWeekKey = candidate ?? this.weekKeys[this.weekKeys.length - 1];
			},

			currentWeekIndex() {
				if (!this.currentWeekKey) {
					return -1;
				}
				return this.weekKeys.indexOf(this.currentWeekKey);
			},

			canGoPreviousWeek() {
				return this.currentWeekIndex() > 0;
			},

			canGoNextWeek() {
				const idx = this.currentWeekIndex();
				return idx > -1 && idx < this.weekKeys.length - 1;
			},

			previousWeek() {
				const idx = this.currentWeekIndex();
				if (idx <= 0) {
					return;
				}
				this.currentWeekKey = this.weekKeys[idx - 1];
			},

			nextWeek() {
				const idx = this.currentWeekIndex();
				if (idx === -1 || idx >= this.weekKeys.length - 1) {
					return;
				}
				this.currentWeekKey = this.weekKeys[idx + 1];
			},

			filteredEventsForWeek(weekKey) {
				const events = this.eventsByWeek[weekKey] ?? [];
				if (this.selectedCourse === 'all') {
					return events;
				}
				return events.filter((evt) => evt.course?.id === this.selectedCourse);
			},

			visibleDays() {
				if (!this.currentWeekKey) {
					return [];
				}

				const start = new Date(`${this.currentWeekKey}T00:00:00`);
				if (Number.isNaN(start.getTime())) {
					return [];
				}

				const todayIso = formatDateKey(new Date());
				const filtered = this.filteredEventsForWeek(this.currentWeekKey);
				const grouped = {};

				filtered.forEach((evt) => {
					if (!grouped[evt.dayIso]) {
						grouped[evt.dayIso] = [];
					}
					grouped[evt.dayIso].push(evt);
				});

				Object.values(grouped).forEach((list) => {
					list.sort((a, b) => a.startDate.getTime() - b.startDate.getTime());
				});

				const days = [];
				for (let offset = 0; offset < 7; offset += 1) {
					const date = new Date(start);
					date.setDate(start.getDate() + offset);
					const iso = formatDateKey(date);

					days.push({
						iso,
						label: capitalizeFirst(formatDayLabel(date, 'long')),
						shortLabel: capitalizeFirst(formatDayLabel(date, 'short')),
						dayNumber: date.getDate(),
						monthLabel: capitalizeFirst(formatMonthLabel(date)),
						events: grouped[iso] ?? [],
						isToday: iso === todayIso,
					});
				}

				return days;
			},

			resetFilters() {
				this.selectedCourse = 'all';
			},

			hasFiltersActive() {
				return this.selectedCourse !== 'all';
			},

			async refresh() {
				if (!this.dataUrl) {
					return;
				}

				this.loading = true;
				this.error = null;
				const previousWeek = this.currentWeekKey;
				try {
					const response = await fetch(this.dataUrl, {
						headers: {
							Accept: 'application/json',
						},
					});

					if (!response.ok) {
						throw new Error(`Erro ao contactar o servidor (HTTP ${response.status})`);
					}

					const data = await response.json();
					this.academicTerm = data.academicTerm ?? null;
					this.rawEvents = Array.isArray(data.events) ? data.events : [];
					this.prepare();

					if (previousWeek && this.eventsByWeek[previousWeek]) {
						this.currentWeekKey = previousWeek;
					} else {
						this.goToCurrentWeek();
					}
				} catch (error) {
					const message = error instanceof Error ? error.message : 'Não foi possível atualizar o horário.';
					this.error = message;
				} finally {
					this.loading = false;
				}
			},

			weekLabel() {
				if (!this.currentWeekKey) {
					return 'Sem semanas disponíveis';
				}

				const start = new Date(`${this.currentWeekKey}T00:00:00`);
				if (Number.isNaN(start.getTime())) {
					return this.currentWeekKey;
				}

				const end = new Date(start);
				end.setDate(start.getDate() + 6);

				return `${formatDateRangeLabel(start)} - ${formatDateRangeLabel(end)}`;
			},

			eventStyle(event) {
				if (!event?.colors) {
					return {};
				}

				return {
					borderColor: event.colors.border,
					backgroundImage: `linear-gradient(135deg, ${event.colors.bg} 0%, ${event.colors.bgAccent} 100%)`,
					color: event.colors.text,
				};
			},

			courseTitle(course) {
				if (!course) {
					return '';
				}
				return course.name || course.acronym || '';
			},

			nextSessionLabel() {
				const source = this.hasFiltersActive()
					? this.enhancedEvents.filter((evt) => evt.course?.id === this.selectedCourse)
					: this.enhancedEvents;

				if (source.length === 0) {
					return 'Sem próximas aulas.';
				}

				const now = new Date();
				const upcoming = source.find((evt) => {
					if (evt.isOngoing) {
						return true;
					}
					return evt.startDate.getTime() >= now.getTime();
				});

				if (!upcoming) {
					return 'Sem próximas aulas.';
				}

				const label = formatWithIntl(
					upcoming.startDate,
					{
						weekday: 'short',
						day: '2-digit',
						month: 'short',
						hour: '2-digit',
						minute: '2-digit',
					},
					upcoming.startDate.toLocaleString()
				);

				const acronym = upcoming.course?.acronym;
				return acronym ? `Próxima aula: ${label} (${acronym})` : `Próxima aula: ${label}`;
			},
		};
	};
}

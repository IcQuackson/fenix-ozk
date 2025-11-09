export default function setupEvaluationsBadges() {
	const items = document.querySelectorAll("#next-evaluations [data-exam-at]");
	if (!items.length) return;

	const badgeStyles = {
		today: ["bg-rose-500/15", "text-rose-100", "border-rose-400/40", "ring-rose-500/30"],
		urgent: ["bg-amber-500/15", "text-amber-100", "border-amber-400/40", "ring-amber-500/30"],
		soon: ["bg-sky-500/15", "text-sky-100", "border-sky-400/40", "ring-sky-500/20"],
		later: ["bg-emerald-500/10", "text-emerald-100", "border-emerald-400/30", "ring-emerald-500/20"]
	};

	const removableClasses = [
		...new Set(Object.values(badgeStyles).flat())
	];

	const applyBadgeStyles = (badge, variant) => {
		badge.classList.remove(...removableClasses, "hidden");
		badge.classList.add(...(badgeStyles[variant] || []));
	};

	const today = new Date();
	today.setHours(0, 0, 0, 0);

	items.forEach(item => {
		const examAt = item.getAttribute("data-exam-at");
		const badge = item.querySelector(".badge-days");

		if (!examAt || !badge) return;

		// Example format: "02/10/2025 18:00 - 19:00"
		const match = examAt.match(/^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2})/);
		if (!match) return;

		const [, day, month, year, hour, minute] = match;
		const examDate = new Date(
			parseInt(year),
			parseInt(month) - 1,
			parseInt(day),
			parseInt(hour),
			parseInt(minute)
		);

		examDate.setHours(0, 0, 0, 0);

		const diffTime = examDate - today;
		const daysLeft = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

		if (daysLeft < 0) return; // already passed

		if (daysLeft === 0) {
			badge.textContent = "Hoje";
			applyBadgeStyles(badge, "today");
		} else {
			badge.textContent = `${daysLeft} dia${daysLeft > 1 ? "s" : ""}`;

			if (daysLeft <= 3) {
				applyBadgeStyles(badge, "urgent");
			} else if (daysLeft <= 7) {
				applyBadgeStyles(badge, "soon");
			} else {
				applyBadgeStyles(badge, "later");
			}
		}
	});
}

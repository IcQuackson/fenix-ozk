export default function setupEvaluationsBadges() {
	const items = document.querySelectorAll("#next-evaluations [data-exam-at]");
	if (!items.length) return;

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

		badge.classList.remove("hidden");

		if (daysLeft === 0) {
			badge.textContent = "Hoje";
			badge.classList.add("bg-red-600", "text-white");
		} else {
			badge.textContent = `${daysLeft} dia${daysLeft > 1 ? "s" : ""}`;

			if (daysLeft <= 3) {
				badge.classList.add("bg-red-600", "text-white");
			} else if (daysLeft <= 7) {
				badge.classList.add("bg-yellow-500", "text-black");
			} else {
				badge.classList.add("bg-green-600", "text-white");
			}
		}
	});
}

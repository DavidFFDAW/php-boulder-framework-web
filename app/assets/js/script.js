const ALERT_TIMEOUT = 3 * 1000;


function toggleSidebar() {
	const sidebar = document.querySelector('.sidebar.admin-sidebar');
	sidebar.classList.toggle('sidebar-closed');
}

function updateImages() {
	const images = [...document.querySelectorAll('img')];
	if (images.length === 0) return false;
	
	for (const image of images) {
		image.draggable = false;
		if (!image.dataset.fallback) image.dataset.fallback = 'https://placehold.co/150';
		
		image.onerror = () => {
			image.width = 150;
			image.height = 150;
			image.src = image.dataset.fallback;
		};
	}
}

function autoEliminateAlerts() { 
	const alerts = [...document.querySelectorAll('.alert-messages .alert')];
	if (alerts.length === 0) return false;

	for (const alert of alerts) {
		alert.classList.add('alert-active');
		setTimeout(() => {
			alert.classList.remove('alert-active');
			alert.ontransitionend = () => alert.remove();
		}, ALERT_TIMEOUT);
	}
}

function addAlert(message, type = 'info') { 
	const alert = document.createElement('div');
	alert.classList.add('alert', `alert-${type}`);
	alert.innerHTML = message;
	document.querySelector('.alert-messages').appendChild(alert);
	autoEliminateAlerts();
}

async function sendFormData(form) { 
	const formData = new FormData(form);
	const response = await fetch(form.action, {
		method: form.method,
		body: formData
	});
	const data = await response.json();
	const alertType = response.ok ? 'success' : 'danger';
	const redirect = formData.get('redirect') || '';
	const defaultMessage = response.ok ? 'Operación realizada con éxito' : 'Ha ocurrido un error';
	const message = data.message || defaultMessage;
	addAlert(message, alertType);

	if (response.ok) {
		setTimeout(() => {
			form.reset();
			if (redirect) return window.location.assign(redirect);
			return window.location.reload();
		}, ALERT_TIMEOUT + 250);
	}
}

function setAsyncForms() { 
	const forms = [...document.querySelectorAll('form[data-async]')];
	console.log(forms);	
	if (forms.length === 0) return false;

	for (const form of forms) {
		form.onsubmit = async (e) => {
			e.preventDefault();
			await sendFormData(form);
		};
	}
}

function setUpFormActions() {
	const forms = [...document.querySelectorAll('form[data-action]')];
	if (forms.length === 0) return false;

	const formWithActions = forms.find(form => form.action !== '');
	if (!formWithActions) return false;

	for (const form of forms) {
		form.action = '';
		const hidden = document.createElement('input');
		hidden.type = 'hidden';
		hidden.name = '_action';
		hidden.value = form.dataset.action;
		form.appendChild(hidden);
	}
}

function executeOnLoad() {
	updateImages();
	autoEliminateAlerts();
	setAsyncForms();
	setUpFormActions();
}

window.onload = executeOnLoad;
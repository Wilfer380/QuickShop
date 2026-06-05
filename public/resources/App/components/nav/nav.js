const categoriesToggle = document.getElementById('btn_categories_menu');
const categoriesMenu = document.getElementById('select_option_categories');
const userToggle = document.getElementById('btn_option_users');
const userMenu = document.getElementById('select_option_users');

const toggleMenu = (menu) => {
    if (!menu) {
        return;
    }

    menu.classList.toggle('hidden');
};

const syncExpanded = (button, menu) => {
    if (!button || !menu) {
        return;
    }

    button.setAttribute('aria-expanded', String(!menu.classList.contains('hidden')));
};

categoriesToggle?.addEventListener('click', () => {
    toggleMenu(categoriesMenu);
    userMenu?.classList.add('hidden');
    syncExpanded(categoriesToggle, categoriesMenu);
    syncExpanded(userToggle, userMenu);
});

userToggle?.addEventListener('click', () => {
    toggleMenu(userMenu);
    categoriesMenu?.classList.add('hidden');
    syncExpanded(userToggle, userMenu);
    syncExpanded(categoriesToggle, categoriesMenu);
});

document.addEventListener('click', (event) => {
    if (categoriesMenu && categoriesToggle && !categoriesMenu.contains(event.target) && !categoriesToggle.contains(event.target)) {
        categoriesMenu.classList.add('hidden');
        categoriesToggle.setAttribute('aria-expanded', 'false');
    }

    if (userMenu && userToggle && !userMenu.contains(event.target) && !userToggle.contains(event.target)) {
        userMenu.classList.add('hidden');
        userToggle.setAttribute('aria-expanded', 'false');
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
        return;
    }

    categoriesMenu?.classList.add('hidden');
    userMenu?.classList.add('hidden');
    categoriesToggle?.setAttribute('aria-expanded', 'false');
    userToggle?.setAttribute('aria-expanded', 'false');
});

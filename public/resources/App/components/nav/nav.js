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

categoriesToggle?.addEventListener('click', () => {
    toggleMenu(categoriesMenu);
    userMenu?.classList.add('hidden');
});

userToggle?.addEventListener('click', () => {
    toggleMenu(userMenu);
    categoriesMenu?.classList.add('hidden');
});

document.addEventListener('click', (event) => {
    if (categoriesMenu && categoriesToggle && !categoriesMenu.contains(event.target) && !categoriesToggle.contains(event.target)) {
        categoriesMenu.classList.add('hidden');
    }

    if (userMenu && userToggle && !userMenu.contains(event.target) && !userToggle.contains(event.target)) {
        userMenu.classList.add('hidden');
    }
});

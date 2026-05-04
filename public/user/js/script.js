// href clicked active link start
document.addEventListener('DOMContentLoaded', () => {
  const currentHTMLPage = window.location.pathname;
  const navLinks = document.querySelectorAll('.nav-link');
  navLinks.forEach(link => link.classList.remove('active'));

  navLinks.forEach(link => {
    const linkPath = new URL(link.href).pathname;
    if (currentHTMLPage === linkPath) {
      link.classList.add('active');
    }
  });
});


// category active link start
document.addEventListener('DOMContentLoaded', function () {
  const categoryPh = document.querySelector('.category-scroll-container');
  const categoryLinks = document.querySelectorAll('.category-link');

  const currentURL = new URL(window.location.href);
  const currentPath = currentURL.pathname + currentURL.search;

  categoryLinks.forEach(link => {
      const linkURL = new URL(link.href, window.location.origin);
      const linkPath = linkURL.pathname + linkURL.search;

      if (currentPath === linkPath) {
          link.classList.add('active');
      }

      link.addEventListener('click', function () {
          sessionStorage.setItem('category-scroll-x', categoryPh.scrollLeft);
      });
  });

  const categoryScrollX = sessionStorage.getItem('category-scroll-x');
  if (categoryScrollX !== null) {
      categoryPh.scrollLeft = parseInt(categoryScrollX, 10);
  }
});


// payment info show
// document.addEventListener('DOMContentLoaded', function () {
//     const paymentMethodSelect = document.getElementById('paymentMethod');
//     const paymentInfoSections = document.querySelectorAll('.payment-info');

//     function togglePaymentInfo() {
//         const selectedOption = paymentMethodSelect.options[paymentMethodSelect.selectedIndex];
//         const targetId = selectedOption.getAttribute('data-target');

//         paymentInfoSections.forEach(section => {
//             section.style.display = 'none';
//         });

//         if (targetId) {
//             const targetElement = document.getElementById(targetId);
//             if (targetElement) {
//                 targetElement.style.display = 'flex';
//             }
//         }
//     }

//     paymentMethodSelect.addEventListener('change', togglePaymentInfo);
//     togglePaymentInfo();
// });


// updatebtn disabled check
document.querySelectorAll('.update-btn-group').forEach(form => {
    const inputs = form.querySelectorAll('input,textarea');
    const updateBtn = form.querySelector('.update-btn');
    const initialValues = {};

    inputs.forEach(input => {
        initialValues[input.name] = input.value;
    });

    function checkUpdateValueForBtn() {
        let hasChanged = false;
        inputs.forEach(input => {
            if (input.value !== initialValues[input.name]) {
                hasChanged = true;
            }
        });
        updateBtn.disabled = !hasChanged;
    }

    inputs.forEach(input => {
        input.addEventListener('input', checkUpdateValueForBtn);
        input.addEventListener('change', checkUpdateValueForBtn);
    });

    checkUpdateValueForBtn();
});


var loadFile = function (event) {
  var image = document.getElementById("screenshot-output");
  image.src = URL.createObjectURL(event.target.files[0]);
};

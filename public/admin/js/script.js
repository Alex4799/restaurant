//...................................nav bar offcanva start .............................................
document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.querySelector('.btn-toggle');
    const offcanvas = document.querySelector('#nav');
    const closeButton = document.querySelector('.close-btn');

    if (toggleButton && offcanvas && closeButton) {
        toggleButton.addEventListener('click', function (e) {
            e.stopPropagation();
            offcanvas.classList.toggle('active');

            // if (offcanvas.classList.contains('active')) {
            //     document.body.style.overflow = 'hidden';
            // } else {
            //     document.body.style.overflow = '';
            // }
        });

        closeButton.addEventListener('click', function () {
            offcanvas.classList.remove('active');
            document.body.style.overflow = '';
        });

        document.addEventListener('click', function (e) {
            if (offcanvas.classList.contains('active') && !offcanvas.contains(e.target) && !toggleButton.contains(e.target)) {
                offcanvas.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
    else{
        console.error('Offcanvas elements not found');
    }
});
//...................................nav bar offcanva end................................................


document.addEventListener('DOMContentLoaded', function () {
  flatpickr("#date", {
      dateFormat: "Y-m-d", // YYYY-MM-DD format
      allowInput: true,   // Let users type dates manually
  });
});



//...................................nav bar link bg start................................................
document.addEventListener('DOMContentLoaded', function () {
    const currentHTMLPage = window.location.href;
    const mainLinks = document.querySelectorAll('.nav-content');
    const subLinks = document.querySelectorAll('.nav-link');
    const sidebar = document.querySelector('.nav-body ul');

    function activateMainLink(mainLink){
      mainLink.classList.add('active');
      const parentUl = mainLink.getAttribute('data-bs-target');
      const collapseElement = document.querySelector(parentUl);
      if (collapseElement){
        collapseElement.classList.add('show');
      }
    }

    subLinks.forEach(subLink => {
      if (currentHTMLPage.includes(subLink.getAttribute('href'))) {
        subLink.classList.add('active');
        const mainLink = subLink.closest('ul').previousElementSibling;
        if (mainLink && mainLink.classList.contains('nav-content')){
          activateMainLink(mainLink);
        }
      }
    });

    mainLinks.forEach(link => link.classList.remove('active'));

    mainLinks.forEach(link => {
      if (currentHTMLPage.includes(link.getAttribute('href'))) {
        link.classList.add('active');
      }
    });

    sidebar.scrollTop = +sessionStorage.getItem('sidebar-scroll-position') || 0;
    sidebar.addEventListener('scroll', () =>
      sessionStorage.setItem('sidebar-scroll-position', sidebar.scrollTop)
    );
  });
//...................................nav bar link bg end................................................


// .......................................... light mode toggle start .................................
document.addEventListener('DOMContentLoaded', function () {
  const body =  document.documentElement;
  const toggleCheckbox = document.querySelector('#theme-toggle');

  const savedTheme = localStorage.getItem('theme');
  toggleCheckbox.checked = savedTheme === 'dark';

  function setTheme(isDarkMode) {
      if (isDarkMode) {
          body.classList.add('dark');
          localStorage.setItem('theme', 'dark');
      } else {
          body.classList.remove('dark');
          localStorage.setItem('theme', 'light');
      }
  }

  toggleCheckbox.addEventListener('change', function () {
      const isDarkMode = toggleCheckbox.checked;
      setTheme(isDarkMode);
  });
});
// ........................................... light mode toggle end.................................


// ....................................... add note form toggle start .................................
$(document).ready(function(){
  $('.add-note-form').hide();

  $('.add-note-btn').click(function(){
    $(this).closest('.pl-container').find('.add-note-form').slideToggle();
  });
});
// .........................................add note form toggle end .................................


$(document).ready(function(){
  $('.custom-time').hide();

  $('.custom-btn').click(function(event){
      event.stopPropagation();
      $('.custom-time').slideToggle();
  });

  $('.dropdown').on('hidden.bs.dropdown', function(){
      $('.custom-time').slideUp();
  });

  $('.time-cancel-btn').click(function(event){
    event.preventDefault();
    $('.start-time').val('');
    $('.end-time').val('');

    $('.dropdown').removeClass('show');
    $('.dropdown-menu').removeClass('show');
});
});


// ................................ profile update click start ........................................
const detailsText = document.getElementsByClassName('text-content');
const updateProfile = document.getElementById('update-profile');
const changePassword = document.getElementById('change-pass');
let currentDetails = 0;

function detailsClick(event,elementId){
    event.preventDefault();
    let clickText = event.target;

    if(clickText.classList.contains('text-active')){
      return;
    }
    for(let d of detailsText){
      d.classList.remove('text-active');
    }
    clickText.classList.add('text-active');
    currentDetails = Array.from(detailsText).indexOf(clickText);

    updateProfile.style.display = elementId === 'updateProfile' ? 'block' : 'none';
    changePassword.style.display = elementId === 'changePassword' ? 'block' : 'none';
}
// ................................ profile update click end ...........................

// input image view
var loadFile = function (event) {
  var image = document.getElementById("output");
  image.src = URL.createObjectURL(event.target.files[0]);
};

var loadFile = function (event) {
  var image = document.getElementById("logo-output");
  image.src = URL.createObjectURL(event.target.files[0]);
};


// ................................ color customize start ...........................
document.addEventListener('DOMContentLoaded', function () {
  const greenColorInput = document.getElementById('greenColor');
  const darkGrayColorInput = document.getElementById('darkGrayColor');
  const saveGreenColorButton = document.getElementById('saveGreenColor');
  const savedarkGrayColorButton = document.getElementById('savedarkGrayColor');
  const resetGreenColorButton = document.getElementById('resetGreenColor');
  const resetdarkGrayColorButton = document.getElementById('resetdarkGrayColor');

  if (greenColorInput && saveGreenColorButton && resetGreenColorButton) {
    const savedGreen = localStorage.getItem('newGreenColor');
    if (savedGreen) {
      document.documentElement.style.setProperty('--green', savedGreen);
      greenColorInput.value = savedGreen;
    }

    saveGreenColorButton.addEventListener('click', function () {
      const selectedGreen = greenColorInput.value;
      document.documentElement.style.setProperty('--green', selectedGreen);
      localStorage.setItem('newGreenColor', selectedGreen);
      alert('The selected color has been saved successfully.');
    });

    resetGreenColorButton.addEventListener('click', function () {
      document.documentElement.style.setProperty('--green', '#55a963');
      localStorage.removeItem('newGreenColor');
      greenColorInput.value = '#55a963';
      alert('The default color has been restored.');
    });
  }

  if (darkGrayColorInput && savedarkGrayColorButton && resetdarkGrayColorButton) {
    const saveddarkGray = localStorage.getItem('newdarkGrayColor');
    if (saveddarkGray) {
      document.documentElement.style.setProperty('--dark-gray', saveddarkGray);
      darkGrayColorInput.value = saveddarkGray;
    }

    savedarkGrayColorButton.addEventListener('click', function () {
      const selecteddarkGray = darkGrayColorInput.value;
      document.documentElement.style.setProperty('--dark-gray', selecteddarkGray);
      localStorage.setItem('newdarkGrayColor', selecteddarkGray);
      alert('The selected color has been saved successfully.');
    });

    resetdarkGrayColorButton.addEventListener('click', function () {
      document.documentElement.style.setProperty('--dark-gray', '#7E7C7C');
      localStorage.removeItem('newdarkGrayColor');
      darkGrayColorInput.value = '#7E7C7C';
      alert('The default color has been restored.');
    });
  }
});
// ................................ color customize end ...........................




//................................... sale category link active start ........................................
const saleCategoryLink = document.getElementsByClassName('sale-category-link');
const productList = document.getElementById('product-list');
const scanProduct = document.getElementById('scan-product');
const searchContainer = document.querySelector('.search-container');
const productCategory = document.querySelector('.product-list-category');

function saleClick(event, elementId) {
    event.preventDefault();
    const clickSale = event.target;

    if (clickSale.classList.contains('sale-category-active')) {
        return;
    }

    for (let link of saleCategoryLink) {
        link.classList.remove('sale-category-active');
    }

    clickSale.classList.add('sale-category-active');
    const clickedIndex = Array.from(saleCategoryLink).indexOf(clickSale);
    localStorage.setItem('currentSaleLink', clickedIndex);

    if (elementId === 'productList') {
        productList.style.display = 'block';
        scanProduct.style.display = 'none';
        searchContainer.style.display = 'flex';
        productCategory.style.display = 'block';
    } else {
        scanProduct.style.display = 'block';
        productList.style.display = 'none';
        searchContainer.style.display = 'none';
        productCategory.style.display = 'none';
    }
}

window.addEventListener('load', () => {
    const lastClickedIndex = localStorage.getItem('currentSaleLink');
    if (lastClickedIndex !== null) {
        const lastLink = saleCategoryLink[lastClickedIndex];
        if (lastLink) {
            for (let link of saleCategoryLink) {
                link.classList.remove('sale-category-active');
            }
            lastLink.classList.add('sale-category-active');
            if (lastLink.textContent.trim() === 'Product List') {
                productList.style.display = 'block';
                scanProduct.style.display = 'none';
                searchContainer.style.display = 'flex';
                productCategory.style.display = 'block';
            } else if (lastLink.textContent.trim() === 'Scan Barcode') {
                scanProduct.style.display = 'block';
                productList.style.display = 'none';
                searchContainer.style.display = 'none';
                productCategory.style.display = 'none';
            }
        }
    } else {
        saleCategoryLink[0].classList.add('sale-category-active');
        productList.style.display = 'block';
        scanProduct.style.display = 'none';
        searchContainer.style.display = 'flex';
        productCategory.style.display = 'block';
    }
});

//.................................... sale category link active end . .........................................

// ................................ sale type btn start ...........................
$(document).ready(function(){
  let storeGraph = localStorage.getItem('activeGraph') || 'gross-sales' ;

  $('.chart-area > div').hide();
  $('#' + storeGraph).css('opacity','1').show();

  $('.sale-tag').removeClass('sales-active');
  $(`.sale-tag[data-target="${storeGraph}"]`).addClass('sales-active');

  $('.sale-tag').click(function(){
    let target = $(this).data('target');
    localStorage.setItem('activeGraph', target);

    $('.sale-tag').removeClass('sales-active');
    $(this).addClass('sales-active');

    $('.chart-area > div').hide();
    $('#' + target).fadeIn();
  });

  let scrollPosition = localStorage.getItem('scrollX') || 0;
  $('.overflow-x-auto').scrollLeft(scrollPosition);

  $('.overflow-x-auto').on('scroll',function(){
    localStorage.setItem('scrollX', $(this).scrollLeft());
  });
});

// ................................ sale type btn end ...........................





//////////! developed by alex and hp !//////////

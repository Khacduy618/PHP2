// Main Js File
$(document).ready(function () {
    'use strict';

    owlCarousels();
    quantityInputs();

    // Header Search Toggle

    var $searchWrapper = $('.header-search-wrapper'),
        $body = $('body'),
        $searchToggle = $('.search-toggle');

    $searchToggle.on('click', function (e) {
        $searchWrapper.toggleClass('show');
        $(this).toggleClass('active');
        $searchWrapper.find('input').focus();
        e.preventDefault();
    });

    $body.on('click', function (e) {
        if ($searchWrapper.hasClass('show')) {
            $searchWrapper.removeClass('show');
            $searchToggle.removeClass('active');
            $body.removeClass('is-search-active');
        }
    });

    $('.header-search').on('click', function (e) {
        e.stopPropagation();
    });

    // Sticky header 
    var catDropdown = $('.category-dropdown'),
        catInitVal = catDropdown.data('visible');

    if ($('.sticky-header').length && $(window).width() >= 992) {
        var sticky = new Waypoint.Sticky({
            element: $('.sticky-header')[0],
            stuckClass: 'fixed',
            offset: -300,
            handler: function (direction) {
                // Show category dropdown
                if (catInitVal && direction == 'up') {
                    catDropdown.addClass('show').find('.dropdown-menu').addClass('show');
                    catDropdown.find('.dropdown-toggle').attr('aria-expanded', 'true');
                    return false;
                }

                // Hide category dropdown on fixed header
                if (catDropdown.hasClass('show')) {
                    catDropdown.removeClass('show').find('.dropdown-menu').removeClass('show');
                    catDropdown.find('.dropdown-toggle').attr('aria-expanded', 'false');
                }
            }
        });
    }

    // Menu init with superfish plugin
    if ($.fn.superfish) {
        $('.menu, .menu-vertical').superfish({
            popUpSelector: 'ul, .megamenu',
            hoverClass: 'show',
            delay: 0,
            speed: 80,
            speedOut: 80,
            autoArrows: true
        });
    }

    // Mobile Menu Toggle - Show & Hide
    $('.mobile-menu-toggler').on('click', function (e) {
        $body.toggleClass('mmenu-active');
        $(this).toggleClass('active');
        e.preventDefault();
    });

    $('.mobile-menu-overlay, .mobile-menu-close').on('click', function (e) {
        $body.removeClass('mmenu-active');
        $('.menu-toggler').removeClass('active');
        e.preventDefault();
    });

    // Add Mobile menu icon arrows to items with children
    $('.mobile-menu').find('li').each(function () {
        var $this = $(this);

        if ($this.find('ul').length) {
            $('<span/>', {
                'class': 'mmenu-btn'
            }).appendTo($this.children('a'));
        }
    });

    // Mobile Menu toggle children menu
    $('.mmenu-btn').on('click', function (e) {
        var $parent = $(this).closest('li'),
            $targetUl = $parent.find('ul').eq(0);

        if (!$parent.hasClass('open')) {
            $targetUl.slideDown(300, function () {
                $parent.addClass('open');
            });
        } else {
            $targetUl.slideUp(300, function () {
                $parent.removeClass('open');
            });
        }

        e.stopPropagation();
        e.preventDefault();
    });

    // Sidebar Filter - Show & Hide
    var $sidebarToggler = $('.sidebar-toggler');
    $sidebarToggler.on('click', function (e) {
        $body.toggleClass('sidebar-filter-active');
        $(this).toggleClass('active');
        e.preventDefault();
    });

    $('.sidebar-filter-overlay').on('click', function (e) {
        $body.removeClass('sidebar-filter-active');
        $sidebarToggler.removeClass('active');
        e.preventDefault();
    });

    // Clear All checkbox/remove filters in sidebar filter
    $('.sidebar-filter-clear').on('click', function (e) {
        $('.sidebar-shop').find('input').prop('checked', false);

        e.preventDefault();
    });

    // Popup - Iframe Video - Map etc.
    if ($.fn.magnificPopup) {
        $('.btn-iframe').magnificPopup({
            type: 'iframe',
            removalDelay: 600,
            preloader: false,
            fixedContentPos: false,
            closeBtnInside: false
        });
    }

    // Product hover
    if ($.fn.hoverIntent) {
        $('.product-3').hoverIntent(function () {
            var $this = $(this),
                animDiff = ($this.outerHeight() - ($this.find('.product-body').outerHeight() + $this.find('.product-media').outerHeight())),
                animDistance = ($this.find('.product-footer').outerHeight() - animDiff);

            $this.find('.product-footer').css({ 'visibility': 'visible', 'transform': 'translateY(0)' });
            $this.find('.product-body').css('transform', 'translateY(' + -animDistance + 'px)');

        }, function () {
            var $this = $(this);

            $this.find('.product-footer').css({ 'visibility': 'hidden', 'transform': 'translateY(100%)' });
            $this.find('.product-body').css('transform', 'translateY(0)');
        });
    }

    // Slider For category pages / filter price
    if (typeof noUiSlider === 'object') {
        var priceSlider = document.getElementById('price-slider');

        // Check if #price-slider elem is exists if not return
        // to prevent error logs
        if (priceSlider == null) return;

        noUiSlider.create(priceSlider, {
            start: [0, 750],
            connect: true,
            step: 50,
            margin: 200,
            range: {
                'min': 0,
                'max': 1000
            },
            tooltips: true,
            format: wNumb({
                decimals: 0,
                prefix: '$'
            })
        });

        // Update Price Range
        priceSlider.noUiSlider.on('update', function (values, handle) {
            $('#filter-price-range').text(values.join(' - '));
        });
    }

    // Product countdown
    if ($.fn.countdown) {
        $('.product-countdown').each(function () {
            var $this = $(this),
                untilDate = $this.data('until'),
                compact = $this.data('compact'),
                dateFormat = (!$this.data('format')) ? 'DHMS' : $this.data('format'),
                newLabels = (!$this.data('labels-short')) ?
                    ['Years', 'Months', 'Weeks', 'Days', 'Hours', 'Minutes', 'Seconds'] :
                    ['Years', 'Months', 'Weeks', 'Days', 'Hours', 'Mins', 'Secs'],
                newLabels1 = (!$this.data('labels-short')) ?
                    ['Year', 'Month', 'Week', 'Day', 'Hour', 'Minute', 'Second'] :
                    ['Year', 'Month', 'Week', 'Day', 'Hour', 'Min', 'Sec'];

            var newDate;

            // Split and created again for ie and edge 
            if (!$this.data('relative')) {
                var untilDateArr = untilDate.split(", "), // data-until 2019, 10, 8 - yy,mm,dd
                    newDate = new Date(untilDateArr[0], untilDateArr[1] - 1, untilDateArr[2]);
            } else {
                newDate = untilDate;
            }

            $this.countdown({
                until: newDate,
                format: dateFormat,
                padZeroes: true,
                compact: compact,
                compactLabels: ['y', 'm', 'w', ' days,'],
                timeSeparator: ' : ',
                labels: newLabels,
                labels1: newLabels1

            });
        });

        // Pause
        // $('.product-countdown').countdown('pause');
    }

    // Quantity Input - Cart page - Product Details pages
    function quantityInputs() {
        if ($.fn.inputSpinner) {
            $("input[type='number']").inputSpinner({
                decrementButton: '<i class="icon-minus"></i>',
                incrementButton: '<i class="icon-plus"></i>',
                groupClass: 'input-spinner',
                buttonsClass: 'btn-spinner',
                buttonsWidth: '26px'
            });
        }
    }

    // Sticky Content - Sidebar - Social Icons etc..
    // Wrap elements with <div class="sticky-content"></div> if you want to make it sticky
    if ($.fn.stick_in_parent && $(window).width() >= 992) {
        $('.sticky-content').stick_in_parent({
            offset_top: 80,
            inner_scrolling: false
        });
    }

    function owlCarousels($wrap, options) {
        if ($.fn.owlCarousel) {
            var owlSettings = {
                items: 1,
                loop: true,
                margin: 0,
                responsiveClass: true,
                nav: true,
                navText: ['<i class="icon-angle-left">', '<i class="icon-angle-right">'],
                dots: true,
                smartSpeed: 400,
                autoplay: false,
                autoplayTimeout: 15000
            };
            if (typeof $wrap == 'undefined') {
                $wrap = $('body');
            }
            if (options) {
                owlSettings = $.extend({}, owlSettings, options);
            }

            // Init all carousel
            $wrap.find('[data-toggle="owl"]').each(function () {
                var $this = $(this),
                    newOwlSettings = $.extend({}, owlSettings, $this.data('owl-options'));

                $this.owlCarousel(newOwlSettings);

            });
        }
    }

    // Product Image Zoom plugin - product pages
    if ($.fn.elevateZoom) {
        $('#product-zoom').elevateZoom({
            gallery: 'product-zoom-gallery',
            galleryActiveClass: 'active',
            zoomType: "inner",
            cursor: "crosshair",
            zoomWindowFadeIn: 400,
            zoomWindowFadeOut: 400,
            responsive: true
        });

        // On click change thumbs active item
        $('.product-gallery-item').on('click', function (e) {
            $('#product-zoom-gallery').find('a').removeClass('active');
            $(this).addClass('active');

            e.preventDefault();
        });

        var ez = $('#product-zoom').data('elevateZoom');

        // Open popup - product images
        $('#btn-product-gallery').on('click', function (e) {
            if ($.fn.magnificPopup) {
                $.magnificPopup.open({
                    items: ez.getGalleryList(),
                    type: 'image',
                    gallery: {
                        enabled: true
                    },
                    fixedContentPos: false,
                    removalDelay: 600,
                    closeBtnInside: false
                }, 0);

                e.preventDefault();
            }
        });
    }

    // Product Gallery - product-gallery.html 
    if ($.fn.owlCarousel && $.fn.elevateZoom) {
        var owlProductGallery = $('.product-gallery-carousel');

        owlProductGallery.on('initialized.owl.carousel', function () {
            owlProductGallery.find('.active img').elevateZoom({
                zoomType: "inner",
                cursor: "crosshair",
                zoomWindowFadeIn: 400,
                zoomWindowFadeOut: 400,
                responsive: true
            });
        });

        owlProductGallery.owlCarousel({
            loop: false,
            margin: 0,
            responsiveClass: true,
            nav: true,
            navText: ['<i class="icon-angle-left">', '<i class="icon-angle-right">'],
            dots: false,
            smartSpeed: 400,
            autoplay: false,
            autoplayTimeout: 15000,
            responsive: {
                0: {
                    items: 1
                },
                560: {
                    items: 2
                },
                992: {
                    items: 3
                },
                1200: {
                    items: 3
                }
            }
        });

        owlProductGallery.on('change.owl.carousel', function () {
            $('.zoomContainer').remove();
        });

        owlProductGallery.on('translated.owl.carousel', function () {
            owlProductGallery.find('.active img').elevateZoom({
                zoomType: "inner",
                cursor: "crosshair",
                zoomWindowFadeIn: 400,
                zoomWindowFadeOut: 400,
                responsive: true
            });
        });
    }

    // Product Gallery Separeted- product-sticky.html 
    if ($.fn.elevateZoom) {
        $('.product-separated-item').find('img').elevateZoom({
            zoomType: "inner",
            cursor: "crosshair",
            zoomWindowFadeIn: 400,
            zoomWindowFadeOut: 400,
            responsive: true
        });

        // Create Array for gallery popup
        var galleryArr = [];
        $('.product-gallery-separated').find('img').each(function () {
            var $this = $(this),
                imgSrc = $this.attr('src'),
                imgTitle = $this.attr('alt'),
                obj = { 'src': imgSrc, 'title': imgTitle };

            galleryArr.push(obj);
        })

        $('#btn-separated-gallery').on('click', function (e) {
            if ($.fn.magnificPopup) {
                $.magnificPopup.open({
                    items: galleryArr,
                    type: 'image',
                    gallery: {
                        enabled: true
                    },
                    fixedContentPos: false,
                    removalDelay: 600,
                    closeBtnInside: false
                }, 0);

                e.preventDefault();
            }
        });
    }

    // Checkout discount input - toggle label if input is empty etc...
    $('#checkout-discount-input').on('focus', function () {
        // Hide label on focus
        $(this).parent('form').find('label').css('opacity', 0);
    }).on('blur', function () {
        // Check if input is empty / toggle label
        var $this = $(this);

        if ($this.val().length !== 0) {
            $this.parent('form').find('label').css('opacity', 0);
        } else {
            $this.parent('form').find('label').css('opacity', 1);
        }
    });

    // Dashboard Page Tab Trigger
    $('.tab-trigger-link').on('click', function (e) {
        var targetHref = $(this).attr('href');

        $('.nav-dashboard').find('a[href="' + targetHref + '"]').trigger('click');

        e.preventDefault();
    });

    // Masonry / Grid layout fnction
    function layoutInit(container, selector) {
        $(container).each(function () {
            var $this = $(this);

            $this.isotope({
                itemSelector: selector,
                layoutMode: ($this.data('layout') ? $this.data('layout') : 'masonry')
            });
        });
    }

    function isotopeFilter(filterNav, container) {
        $(filterNav).find('a').on('click', function (e) {
            var $this = $(this),
                filter = $this.attr('data-filter');

            // Remove active class
            $(filterNav).find('.active').removeClass('active');

            // Init filter
            $(container).isotope({
                filter: filter,
                transitionDuration: '0.7s'
            });

            // Add active class
            $this.closest('li').addClass('active');
            e.preventDefault();
        });
    }

    /* Masonry / Grid Layout & Isotope Filter for blog/portfolio etc... */
    if (typeof imagesLoaded === 'function' && $.fn.isotope) {
        // Portfolio
        $('.portfolio-container').imagesLoaded(function () {
            // Portfolio Grid/Masonry
            layoutInit('.portfolio-container', '.portfolio-item'); // container - selector
            // Portfolio Filter
            isotopeFilter('.portfolio-filter', '.portfolio-container'); //filterNav - .container
        });

        // Blog
        $('.entry-container').imagesLoaded(function () {
            // Blog Grid/Masonry
            layoutInit('.entry-container', '.entry-item'); // container - selector
            // Blog Filter
            isotopeFilter('.entry-filter', '.entry-container'); //filterNav - .container
        });

        // Product masonry product-masonry.html
        $('.product-gallery-masonry').imagesLoaded(function () {
            // Products Grid/Masonry
            layoutInit('.product-gallery-masonry', '.product-gallery-item'); // container - selector
        });

        // Products - Demo 11
        $('.products-container').imagesLoaded(function () {
            // Products Grid/Masonry
            layoutInit('.products-container', '.product-item'); // container - selector
            // Product Filter
            isotopeFilter('.product-filter', '.products-container'); //filterNav - .container
        });
    }

    // Count
    var $countItem = $('.count');
    if ($.fn.countTo) {
        if ($.fn.waypoint) {
            $countItem.waypoint(function () {
                $(this.element).countTo();
            }, {
                offset: '90%',
                triggerOnce: true
            });
        } else {
            $countItem.countTo();
        }
    } else {
        // fallback
        // Get the data-to value and add it to element
        $countItem.each(function () {
            var $this = $(this),
                countValue = $this.data('to');
            $this.text(countValue);
        });
    }

    // Scroll To button
    var $scrollTo = $('.scroll-to');
    // If button scroll elements exists
    if ($scrollTo.length) {
        // Scroll to - Animate scroll
        $scrollTo.on('click', function (e) {
            var target = $(this).attr('href'),
                $target = $(target);
            if ($target.length) {
                // Add offset for sticky menu
                var scrolloffset = ($(window).width() >= 992) ? ($target.offset().top - 52) : $target.offset().top
                $('html, body').animate({
                    'scrollTop': scrolloffset
                }, 600);
                e.preventDefault();
            }
        });
    }

    // Review tab/collapse show + scroll to tab
    $('#review-link').on('click', function (e) {
        var target = $(this).attr('href'),
            $target = $(target);

        if ($('#product-accordion-review').length) {
            $('#product-accordion-review').collapse('show');
        }

        if ($target.length) {
            // Add offset for sticky menu
            var scrolloffset = ($(window).width() >= 992) ? ($target.offset().top - 72) : ($target.offset().top - 10)
            $('html, body').animate({
                'scrollTop': scrolloffset
            }, 600);

            $target.tab('show');
        }

        e.preventDefault();
    });

    // Scroll Top Button - Show
    var $scrollTop = $('#scroll-top');

    $(window).on('load scroll', function () {
        if ($(window).scrollTop() >= 400) {
            $scrollTop.addClass('show');
        } else {
            $scrollTop.removeClass('show');
        }
    });

    // On click animate to top
    $scrollTop.on('click', function (e) {
        $('html, body').animate({
            'scrollTop': 0
        }, 800);
        e.preventDefault();
    });

    // Google Map api v3 - Map for contact pages
    if (document.getElementById("map") && typeof google === "object") {

        var content = '<address>' +
            '88 Pine St,<br>' +
            'New York, NY 10005, USA<br>' +
            '<a href="#" class="direction-link" target="_blank">Get Directions <i class="icon-angle-right"></i></a>' +
            '</address>';

        var latLong = new google.maps.LatLng(40.8127911, -73.9624553);

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            center: latLong, // Map Center coordinates
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP

        });

        var infowindow = new google.maps.InfoWindow({
            content: content,
            maxWidth: 360
        });

        var marker;
        marker = new google.maps.Marker({
            position: latLong,
            map: map,
            animation: google.maps.Animation.DROP
        });

        google.maps.event.addListener(marker, 'click', (function (marker) {
            return function () {
                infowindow.open(map, marker);
            }
        })(marker));
    }

    var $viewAll = $('.view-all-demos');
    $viewAll.on('click', function (e) {
        e.preventDefault();
        $('.demo-item.hidden').addClass('show');
        $(this).addClass('disabled-hidden');
    })

    var $megamenu = $('.megamenu-container .sf-with-ul');
    $megamenu.hover(function () {
        $('.demo-item.show').addClass('hidden');
        $('.demo-item.show').removeClass('show');
        $viewAll.removeClass('disabled-hidden');
    });

    // Product quickView popup
    $('.btn-quickview').on('click', function (e) {
        var ajaxUrl = $(this).attr('href');
        if ($.fn.magnificPopup) {
            setTimeout(function () {
                $.magnificPopup.open({
                    type: 'ajax',
                    mainClass: "mfp-ajax-product",
                    tLoading: '',
                    preloader: false,
                    removalDelay: 350,
                    items: {
                        src: ajaxUrl
                    },
                    callbacks: {
                        ajaxContentAdded: function () {
                            owlCarousels($('.quickView-content'), {
                                onTranslate: function (e) {
                                    var $this = $(e.target),
                                        currentIndex = ($this.data('owl.carousel').current() + e.item.count - Math.ceil(e.item.count / 2)) % e.item.count;
                                    $('.quickView-content .carousel-dot').eq(currentIndex).addClass('active').siblings().removeClass('active');
                                }
                            });
                            quantityInputs();
                        },
                        open: function () {
                            $('body').css('overflow-x', 'visible');
                            $('.sticky-header.fixed').css('padding-right', '1.7rem');
                        },
                        close: function () {
                            $('body').css('overflow-x', 'hidden');
                            $('.sticky-header.fixed').css('padding-right', '0');
                        }
                    },

                    ajax: {
                        tError: '',
                    }
                }, 0);
            }, 500);

            e.preventDefault();
        }
    });
    $('body').on('click', '.carousel-dot', function () {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
    });

    $('body').on('click', '.btn-fullscreen', function (e) {
        var galleryArr = [];
        $(this).parents('.owl-stage-outer').find('.owl-item:not(.cloned)').each(function () {
            var $this = $(this).find('img'),
                imgSrc = $this.attr('src'),
                imgTitle = $this.attr('alt'),
                obj = { 'src': imgSrc, 'title': imgTitle };
            galleryArr.push(obj);
        });

        var ajaxUrl = $(this).attr('href');

        var mpInstance = $.magnificPopup.instance;
        if (mpInstance.isOpen)
            mpInstance.close();

        setTimeout(function () {
            $.magnificPopup.open({
                type: 'ajax',
                mainClass: "mfp-ajax-product",
                tLoading: '',
                preloader: false,
                removalDelay: 350,
                items: {
                    src: ajaxUrl
                },
                callbacks: {
                    ajaxContentAdded: function () {
                        owlCarousels($('.quickView-content'), {
                            onTranslate: function (e) {
                                var $this = $(e.target),
                                    currentIndex = ($this.data('owl.carousel').current() + e.item.count - Math.ceil(e.item.count / 2)) % e.item.count;
                                $('.quickView-content .carousel-dot').eq(currentIndex).addClass('active').siblings().removeClass('active');
                                $('.curidx').html(currentIndex + 1);
                            }
                        });
                        quantityInputs();
                    },
                    open: function () {
                        $('body').css('overflow-x', 'visible');
                        $('.sticky-header.fixed').css('padding-right', '1.7rem');
                    },
                    close: function () {
                        $('body').css('overflow-x', 'hidden');
                        $('.sticky-header.fixed').css('padding-right', '0');
                    }
                },

                ajax: {
                    tError: '',
                }
            }, 0);
        }, 500);

        e.preventDefault();
    });

    $('.update-cart-form').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);

        $.ajax({
            url: '/update',
            method: 'POST',
            data: form.serialize(),
            success: function (response) {
                alert('Cart updated successfully!');
                location.reload();
            },
            error: function (xhr, status, error) {
                alert('Failed to update cart. Please try again.');
                console.error('Error:', error);
            }
        });
    });
    //select all cart item
    $('#select-all').on('change', function () {
        $('.checkboxes').prop('checked', $(this).prop('checked'));
    });
    //check out 
    $('form[action="?act=checkout"]').on('submit', function (e) {
        var $form = $(this);
        var selectedItems = $('.checkboxes:checked');
        $form.find('input[name="cart_items[]"]').remove();
        var uniqueItemIds = [];
        selectedItems.each(function () {
            var itemId = $(this).val();
            if (uniqueItemIds.indexOf(itemId) === -1) {
                uniqueItemIds.push(itemId);
                $form.children(
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'cart_items[]')
                        .val(itemId)
                );
            }
        });
    });

    // Confirm order
    $('#form_thanhtoan').on('submit', function (e) {
        if (!confirm('Bạn có chắc chắn muốn đặt hàng?')) {
            e.preventDefault();
        }
    });

    if (document.getElementById('newsletter-popup-form')) {
        setTimeout(function () {
            var mpInstance = $.magnificPopup.instance;
            if (mpInstance.isOpen) {
                mpInstance.close();
            }

            setTimeout(function () {
                $.magnificPopup.open({
                    items: {
                        src: '#newsletter-popup-form'
                    },
                    type: 'inline',
                    removalDelay: 350,
                    callbacks: {
                        open: function () {
                            $('body').css('overflow-x', 'visible');
                            $('.sticky-header.fixed').css('padding-right', '1.7rem');
                        },
                        close: function () {
                            $('body').css('overflow-x', 'hidden');
                            $('.sticky-header.fixed').css('padding-right', '0');
                        }
                    }
                });
            }, 500)
        }, 1000)
    }
    function toggleLabelVisibility() {
        var input = $('#checkout-discount-input');
        var label = $('#coupon-label');
        if (input.val().trim() !== '') {
            label.hide();
        } else {
            label.show();
        }
    }

    // Initial check on page load
    toggleLabelVisibility();

    // Check on input change
    $('#checkout-discount-input').on('input', function() {
        toggleLabelVisibility();
    });

    function formatCurrency(number) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'decimal',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number) + ' đ';
    }

    // Hàm chuyển đổi chuỗi tiền về số
    function parsePrice(priceString) {
        if (!priceString) return 0;
        // Loại bỏ tất cả ký tự không phải số
        return parseInt(priceString.replace(/[^\d]/g, '')) || 0;
    }

    // Hàm cập nhật tất cả các tổng tiền
    function updateTotals() {
        let tong = 0;
        const shipping = 20000;
        const $checkedItems = $('.checkboxes:checked');

        // Chỉ tính tổng khi có item được chọn
        if ($checkedItems.length > 0) {
            $checkedItems.each(function() {
                const $row = $(this).closest('tr');
                const price = parsePrice($row.find('.price-col label').text());
                const quantity = parseInt($row.find('.quantity-input').val());
                tong += price * quantity;
            });
        }

        // Cập nhật subtotal
        $('.summary-subtotal td:last').text(formatCurrency(tong));

        // Tính shipping chỉ khi có item được chọn
        const shippingAmount = $checkedItems.length > 0 ? shipping : 0;
        $('.summary-shipping td:last').text(formatCurrency(shippingAmount));

        // Tính discount chỉ khi có item được chọn
        let discount = 0;
        const $couponRow = $('.summary-coupon');
        if ($couponRow.length && $checkedItems.length > 0) {
            const discountPercent = parseFloat($couponRow.data('discount-percent')) || 0;
            if (discountPercent > 0) {
                discount = Math.round(tong * (discountPercent / 100));
                $('.summary-coupon .discount-amount').text(formatCurrency(discount));
            }
        }

        // Hiển thị/ẩn discount
        if (discount > 0 && $checkedItems.length > 0) {
            $('.summary-coupon').show();
        } else {
            $('.summary-coupon').hide();
        }

        // Tính tổng cuối cùng
        const total = Math.max(0, tong + shippingAmount - discount);
        $('.summary-total td:last').text(formatCurrency(total));
        $('input[name="total"]').val(total);

        // Enable/disable nút checkout dựa trên items được chọn
        $('#btn-order').prop('disabled', $checkedItems.length === 0);
    }
    $('.quantity-in').on('change', function() {
        const $input = $(this);
        const quantity = parseInt($input.val());
        if (quantity < 1) $input.val(1);
        if (quantity > 10) $input.val(10);
    });
    
    // Xử lý sự kiện thay đổi số lượng
    $('.quantity-input').on('change', function() {
        const $input = $(this);
        const quantity = parseInt($input.val());
        const $row = $input.closest('tr');
        const productId = $row.find('.checkboxes').data('product-id');

        console.log('Input changed:', {
            productId: productId,
            quantity: quantity,
            $row: $row
        });

        // Kiểm tra giới hạn số lượng
        if (quantity < 1) $input.val(1);
        if (quantity > 10) $input.val(10);

        const finalQuantity = parseInt($input.val());

        console.log('Sending AJAX request:', {
            url: _WEB_ROOT + '/update-quantity',
            productId: productId,
            finalQuantity: finalQuantity
        });

        // Gửi AJAX request để cập nhật quantity
        $.ajax({
            url: _WEB_ROOT + '/update-quantity',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: finalQuantity
            },
            success: function(response) {
                console.log('AJAX success response:', response);
                try {
                    const data = JSON.parse(response);
                    console.log('Parsed response data:', data);
                    
                    if (data.success) {
                        // Luôn cập nhật tổng tiền của sản phẩm
                        const price = parsePrice($row.find('.price-col label').text());
                        const newTotal = price * finalQuantity;
                        console.log('Updating totals:', {
                            price: price,
                            newTotal: newTotal,
                            finalQuantity: finalQuantity
                        });
                        
                        $row.find('.total-col label').text(formatCurrency(newTotal));
                        updateTotals();
                    } else {
                        console.error('AJAX request failed:', data.message);
                        alert(data.message || 'Failed to update quantity');
                        $input.val($input.data('previous-value'));
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    alert('Error processing response');
                    $input.val($input.data('previous-value'));
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                alert('Error updating quantity');
                $input.val($input.data('previous-value'));
            }
        });

        // Store current value for potential rollback
        $input.data('previous-value', finalQuantity);
    });

    // Hàm xử lý select all checkboxes
    function selectAllCheckboxes() {
        const isChecked = $('.select-all-checkbox').is(':checked');
        $('.checkboxes').prop('checked', isChecked);
        updateSelectedItems();
        updateTotals();
    }

    // Xử lý sự kiện checkbox
    $('.checkboxes').on('change', function() {
        updateSelectedItems();
        // Cập nhật trạng thái "select all"
        const allChecked = $('.checkboxes').length === $('.checkboxes:checked').length;
        $('.select-all-checkbox').prop('checked', allChecked);
       
        updateTotals();
    });

    // Xử lý nút "Select All"
    $('.select-all-checkbox').on('change', function() {
        selectAllCheckboxes();
    });
    function updateSelectedItems() {
        let selectedItems = [];
        
        $('.checkboxes:checked').each(function() {
            const $row = $(this).closest('tr');
            const $priceLabel = $row.find('.price-col label');
            const $quantityInput = $row.find('.quantity-input');
            const $totalLabel = $row.find('.total-col label');
            
            selectedItems.push({
                cart_item_id: $(this).val(),
                product_id: $(this).data('product-id'),
                product_name: $row.find('.product-title a').text(),
                product_image: $row.find('.product-image img').attr('src'),
                price: parsePrice($priceLabel.text()),
                quantity: parseInt($quantityInput.val()),
                total: parsePrice($totalLabel.text())
            });
        });

        // Cập nhật input hidden với dữ liệu mới
        $('input[name="selected_items"]').val(JSON.stringify(selectedItems));
        
        // Enable/disable nút checkout dựa trên số lượng item được chọn
        $('#btn-order').prop('disabled', selectedItems.length === 0);
        
        // Cập nhật tổng tiền
        updateTotals();
    }
    function updateCartSummary(subtotal) {
        // Update subtotal
        $('.subtotal-amount').text(formatCurrency(subtotal));
        
        // Tính discount nếu có
        const discountPercent = $('.summary-coupon').data('discount-percent') || 0;
        const discount = subtotal * (discountPercent / 100);
        if(discountPercent > 0) {
            $('.discount-amount').text(formatCurrency(discount));
        }
        
        // Lấy shipping fee
        const shipping = parseFloat($('input[name="shipping"]').val()) || 0;
        
        // Tính tổng
        const total = subtotal + shipping - discount;
        $('.total-amount').text(formatCurrency(total));
        $('input[name="total"]').val(total);
    }
    // Khởi tạo ban đầu
    updateSelectedItems();
    updateTotals();

    // Handler cho nút delete item
    $('.btn-remove').on('click', function(e) {
        e.preventDefault();
        const $row = $(this).closest('tr');
        const deleteUrl = $(this).attr('href');

        // Gọi AJAX để xóa item
        $.ajax({
            url: deleteUrl,
            method: 'POST',
            success: function(response) {
                // Xóa row khỏi table
                $row.fadeOut(300, function() {
                    $(this).remove();
                    // Cập nhật lại totals sau khi xóa
                    updateTotals();
                    
                    // Kiểm tra nếu không còn item nào
                    if ($('.checkboxes').length === 0) {
                        // Thêm row "No products in the cart"
                        $('tbody').append('<tr><td colspan="6" class="text-center">No products in the cart.</td></tr>');
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Could not delete item. Please try again.');
            }
        });
    });

    // Thêm hàm mới để cập nhật bảng summary
    function updateSummaryTable(coupon) {
        // Tính subtotal từ các sản phẩm được chọn
        let subtotal = 0;
        $('.table-summary tbody tr').each(function() {
            // Bỏ qua các hàng tổng
            if (!$(this).hasClass('summary-subtotal') && 
                !$(this).hasClass('summary-total') && 
                !$(this).hasClass('summary-coupon')) {
                const quantity = parseInt($(this).find('td:nth-child(2)').text().replace('x ', '')) || 0;
                const price = parsePrice($(this).find('td:last').text());
                subtotal += price;
            }
        });

        // Lấy shipping fee
        const shipping = 20000; // Giá shipping cố định

        // Tính discount nếu có coupon
        let discount = 0;
        if (coupon && coupon.coupon_discount) {
            discount = Math.round(subtotal * (coupon.coupon_discount / 100));
            
            // Cập nhật hoặc thêm row coupon
            let $couponRow = $('.summary-coupon');
            if ($couponRow.length === 0) {
                $couponRow = $('<tr class="summary-coupon">')
                    .html(`
                        <td>Coupon:</td>
                        <td class="discount-name">${coupon.coupon_name}</td>
                        <td class="discount-amount"></td>
                    `)
                    .insertBefore('.summary-total');
            }
            
            // Cập nhật thông tin coupon
            $couponRow.find('.discount-name').text(coupon.coupon_name);
            $couponRow.find('.discount-amount').text(formatCurrency(discount));
            $couponRow.attr('data-discount-percent', coupon.coupon_discount);
            $couponRow.show();
        } else {
            $('.summary-coupon').hide();
        }

        // Tính total
        const total = subtotal + shipping - discount;

        // Cập nhật hiển thị
        $('.summary-subtotal td:last').text(formatCurrency(subtotal));
        $('.summary-total td:last').text(formatCurrency(total));

        // Cập nhật hidden inputs
        $('input[name="total"]').val(total);
        $('input[name="tong"]').val(subtotal);
        $('input[name="coupon_id"]').val(coupon ? coupon.coupon_id : 0);

        console.log('Summary updated:', {
            subtotal: subtotal,
            shipping: shipping,
            discount: discount,
            total: total,
            coupon: coupon
        });
    }

    // Sửa lại xử lý form coupon
    $('#coupon-form').on('submit', function(e) {
        e.preventDefault();
        const couponName = $('#checkout-discount-input').val().trim();
        
        if (!couponName) {
            alert('Please enter a coupon code');
            return;
        }

        $.ajax({
            url: _WEB_ROOT + '/apply-coupon',
            method: 'POST',
            data: { coupon_name: couponName },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Cập nhật label coupon
                    $('#coupon-label').text(response.coupon.coupon_name);
                    
                    // Cập nhật bảng summary với coupon mới
                    updateSummaryTable(response.coupon);
                    
                    // Thông báo thành công
                    alert('Coupon applied successfully!');
                } else {
                    alert(response.error || 'Invalid coupon code');
                    // Reset form nếu coupon không hợp lệ
                    $('#coupon-label').html('Have a coupon? <span>Click here to enter your code</span>');
                    updateSummaryTable(null);
                }
            },
            error: function(xhr, status, error) {
                console.error('Coupon application failed:', error);
                alert('Could not apply coupon. Please try again.');
            }
        });
    });

    // Thêm hàm mới để tính toán giá trị ban đầu
    function initializeSummaryTable() {
        let subtotal = 0;
        $('.table-summary tbody tr').each(function() {
            // Bỏ qua các hàng tổng
            if (!$(this).hasClass('summary-subtotal') && 
                !$(this).hasClass('summary-total') && 
                !$(this).hasClass('summary-coupon')) {
                const quantity = parseInt($(this).find('td:nth-child(2)').text().replace('x ', '')) || 0;
                const price = parsePrice($(this).find('td:last').text());
                subtotal += price;
            }
        });

        const shipping = 20000; // Giá shipping cố định
        let discount = 0;

        // Kiểm tra nếu đã có coupon
        const $couponRow = $('.summary-coupon');
        if ($couponRow.length > 0) {
            const discountPercent = parseFloat($couponRow.data('discount-percent')) || 0;
            if (discountPercent > 0) {
                discount = Math.round(subtotal * (discountPercent / 100));
            }
        }

        // Tính total
        const total = subtotal + shipping - discount;

        // Cập nhật hiển thị
        $('.summary-subtotal td:last').text(formatCurrency(subtotal));
        $('.summary-total td:last').text(formatCurrency(total));

        // Cập nhật hidden inputs
        $('input[name="total"]').val(total);
        $('input[name="tong"]').val(subtotal);

        console.log('Initial summary values:', {
            subtotal: subtotal,
            shipping: shipping,
            discount: discount,
            total: total
        });
    }

    // Sửa lại phần khởi tạo trong document.ready
    $(document).ready(function() {
        // Khởi tạo giá trị ban đầu
        initializeSummaryTable();

        // Kiểm tra nếu đã có coupon trong session
        const existingCoupon = $('.summary-coupon').data('discount-percent');
        if (existingCoupon) {
            const couponData = {
                coupon_name: $('.summary-coupon .discount-name').text(),
                coupon_discount: existingCoupon,
                coupon_id: $('input[name="coupon_id"]').val()
            };
            updateSummaryTable(couponData);
        }
    });
});

// Define functions outside of document.ready
function initCategoryProducts() {
    console.log('Initializing category products...');
    
    // Load products for first category by default
    const $firstTab = $('.trending .nav-pills .nav-link.active');
    console.log('First active tab:', $firstTab.length ? $firstTab.attr('id') : 'not found');
    
    if($firstTab.length > 0) {
        const firstTabId = $firstTab.attr('id');
        console.log('Loading first tab:', firstTabId);
        loadProductsByCategory(firstTabId.replace('-link', ''));
    }
    
    // Handle tab change
    $('.trending .nav-pills .nav-link').on('click', function (e) {
        e.preventDefault();
        console.log('Tab clicked:', $(this).attr('id'));
        
        // Remove active class from all tabs and add to clicked tab
        $('.trending .nav-pills .nav-link').removeClass('active');
        $(this).addClass('active');
        
        const tabId = $(this).attr('id').replace('-link', '');
        const targetPane = $(`#${tabId}-tab`);
        
        // Hide all panes and show target pane
        $('.trending .tab-pane').removeClass('show active');
        targetPane.addClass('show active');
        
        loadProductsByCategory(tabId);
    });

    // Debug info
    console.log('Elements found:', {
        'nav-pills': $('.trending .nav-pills').length,
        'nav-links': $('.trending .nav-pills .nav-link').length,
        'tab-content': $('.trending .tab-content').length,
        'tab-panes': $('.trending .tab-pane').length
    });
}

function loadProductsByCategory(tabId) {
    console.log('Loading products for tab:', tabId);
    
    if (!tabId) {
        console.error('No tab ID provided');
        return;
    }

    const categoryId = tabId.replace('trending-', '');
    const $container = $(`#trending-${categoryId}-tab .owl-carousel`);
    
    console.log('Making request for category:', {
        categoryId: categoryId,
        url: _WEB_ROOT + '/trending',
        container: $container.length ? 'found' : 'not found'
    });

    // Show loading state
    $container.html('<div class="loading">Loading products...</div>');

    $.ajax({
        url: _WEB_ROOT + '/trending',
        type: 'POST',
        data: {category_id: categoryId},
        dataType: 'json',
        success: function(response) {
            console.log('Response received:', response);
            
            if (!response || response.length === 0) {
                $container.html('<p>No products found for this category.</p>');
                return;
            }

            const html = response.map(product => generateProductHtml(product)).join('');
            console.log('Generated HTML length:', html.length);
            
            $container.html(html);

            // Initialize owl carousel
            if ($container.hasClass('owl-loaded')) {
                $container.trigger('destroy.owl.carousel');
            }
            
            $container.owlCarousel({
                nav: true,
                dots: false,
                margin: 20,
                loop: false,
                responsive: {
                    0: { items: 2 },
                    480: { items: 2 },
                    768: { items: 3 },
                    992: { items: 4 }
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                error: error
            });
            $container.html('<p>Error loading products. Please try again.</p>');
        }
    });
}


// Add new function for top selling products
function initTopSellingProducts() {
    console.log('Initializing top selling products...');
    
    // Load products for first category by default
    const $firstTab = $('.top .nav-pills .nav-link.active');
    console.log('First active tab:', $firstTab.length ? $firstTab.attr('id') : 'not found');
    
    if($firstTab.length > 0) {
        const firstTabId = $firstTab.attr('id');
        console.log('Loading first tab:', firstTabId);
        loadTopSellingProducts(firstTabId);
    }
    
    // Handle tab change
    $('.top .nav-pills .nav-link').on('click', function (e) {
        e.preventDefault();
        const tabId = $(this).attr('id');
        console.log('Tab clicked:', tabId);
        
        $('.top .nav-pills .nav-link').removeClass('active');
        $(this).addClass('active');
        
        const targetId = tabId.replace('-link', '');
        const targetPane = $(`#${targetId}-tab`);
        
        $('.top .tab-pane').removeClass('show active');
        targetPane.addClass('show active');
        
        loadTopSellingProducts(tabId);
    });
}


function loadTopSellingProducts(tabId) {
    console.log('Loading top selling products for tab:', tabId);
    
    if (!tabId) {
        console.error('No tab ID provided');
        return;
    }

    // Không cần thay thế 'top-' nữa vì ID đã đúng format
    const categoryId = tabId.replace('-link', '').replace('top-', '');
    const $container = $(`#${tabId.replace('-link', '')}-tab .owl-carousel`);
    
    console.log('Making request for category:', {
        tabId: tabId,
        categoryId: categoryId,
        url: _WEB_ROOT + '/topSell',
        container: $container.length ? 'found' : 'not found',
        selector: `#${tabId.replace('-link', '')}-tab .owl-carousel`
    });

    if (!$container.length) {
        console.error('Container not found:', `#${tabId.replace('-link', '')}-tab .owl-carousel`);
        return;
    }

    $container.html('<div class="loading">Loading products...</div>');

    $.ajax({
        url: _WEB_ROOT + '/topSell',
        type: 'POST',
        data: {category_id: categoryId},
        dataType: 'json',
        success: function(response) {
            console.log('Response received:', response);
            
            if (!response || response.length === 0) {
                $container.html('<p>No products found for this category.</p>');
                return;
            }

            const html = response.map(product => generateProductHtml(product)).join('');
            console.log('Generated HTML length:', html.length);
            
            $container.html(html);

            if ($container.hasClass('owl-loaded')) {
                $container.trigger('destroy.owl.carousel');
            }
            
            $container.owlCarousel({
                nav: true,
                dots: false,
                margin: 20,
                loop: false,
                responsive: {
                    0: { items: 2 },
                    480: { items: 2 },
                    768: { items: 3 },
                    992: { items: 4 },
                    1200: { items: 5 }
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                error: error,
                url: this.url // Log URL being called
            });
            $container.html('<p>Error loading products. Please try again.</p>');
        }
    });
}

function generateProductHtml(product) {
    console.log('Generating HTML for product:', product);
    
    const discountPrice = product.product_price * (1 - product.product_discount/100);
    return `
        <div class="product product-2">
            <figure class="product-media">
                ${product.product_discount > 0 ? 
                    `<span class="product-label label-circle label-sale">-${product.product_discount}%</span>` 
                    : ''}
                <a href="${_WEB_ROOT}/product-detail/${product.product_id}">
                    <img src="${_WEB_ROOT}/public/uploads/products/${product.product_img}" 
                         alt="${product.product_name}" 
                         class="product-image">
                </a>

                <div class="product-action-vertical">
                    <a href="#" class="btn-product-icon btn-wishlist btn-expandable">
                        <span>add to wishlist</span>
                    </a>
                </div>

                <div class="product-action product-action-dark">
                    <form action="${_WEB_ROOT}/add-to-cart" class="w-50" method="post">
                        
                        <input type="hidden" name="product_id" value="${product.product_id}">
                        <input type="hidden" name="quantity" value="1">
                        
                        <button type="submit" class="btn-product btn-cart "><span>add to cart</span></button>
                        
                    </form>
                    <a href="#" class="btn-product btn-quickview" title="Quick view">
                        <span>quick view</span>
                    </a>
                </div>
            </figure>

            <div class="product-body">
                <div class="product-cat">
                    <a href="${_WEB_ROOT}/product/${product.product_cat}">${product.category_name}</a>
                </div>
                <h3 class="product-title">
                    <a href="${_WEB_ROOT}/product-detail/${product.product_id}">
                        ${product.product_name}
                    </a>
                </h3>
                <div class="product-price">
                    ${product.product_discount > 0 ? 
                        `<span class="new-price">${formatCurrency(discountPrice)}</span>
                         <span class="old-price">${formatCurrency(product.product_price)}</span>` 
                        : formatCurrency(product.product_price)}
                </div>
                <div class="ratings-container">
                    <div class="ratings">
                        <div class="ratings-val" style="width: ${(product.most_common_rating || 0) * 20}%"></div>
                    </div>
                    <span class="ratings-text">
                        (${product.review_count || 0} Reviews )
                    </span>
                </div>
            </div>
        </div>
    `;
}


function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

$(document).ready(function() {
    initCategoryProducts();
    initTopSellingProducts();
});


// Backup initialization
window.addEventListener('load', function() {
console.log('Window loaded');
if (!window.categoryProductsInitialized) {
    initCategoryProducts();
    initTopSellingProducts();
    window.categoryProductsInitialized = true;
}
}
);


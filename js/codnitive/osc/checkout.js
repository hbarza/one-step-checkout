/**
 * CODNITIVE
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE_EULA.html.
 * It is also available through the world-wide-web at this URL:
 * http://www.codnitive.com/en/terms-of-service-softwares/
 * http://www.codnitive.com/fa/terms-of-service-softwares/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   Codnitive
 * @package    Codnitive_OSC
 * @author     Hassan Barza <support@codnitive.com>
 * @copyright  Copyright (c) 2012 CODNITIVE Co. (http://www.codnitive.com)
 * @license    http://www.codnitive.com/en/terms-of-service-softwares/ End User License Agreement (EULA 1.0)
 */

//(function($) {

	Codnitive_OSC = {
//        storeBaseUrl: null,
//        skinUrl: null,
//        updateShippingMethodUrl: null,
//		loadingPaymentMethod: '',
		backgroundOverlayId: 'background-overlay',
		showBlockClassName: 'show',
		billingFormId: 'co-billing-form',
		billingFormUpdater: '.updater',
		shippingFormId: 'co-shipping-form',
		shippingFormUpdater: '.updater',
		shippingMethodFormId: 'co-shipping-method-form',
		shippingMethodFormUpdater: '.radio',
		paymentFormId: 'co-payment-form',
		paymentFormUpdater: '.radio',
		useForShippingId: 'osc-shipping',
		registerPasswordWrapperId: 'register-customer-password',
		loginBlockId: 'osc-login',
		forgotPasswordBlockId: 'osc-forgot-password',

		initialize: function(options){
			this.storeBaseUrl = options.storeBaseUrl;
			this.skinUrl = options.skinUrl;
			this.updateShippingMethodUrl = options.updateShippingMethodUrl;
			this.updatePaymentMethodUrl = options.updatePaymentMethodUrl;
//            this.updateReviewUrl = options.updateReviewUrl;
			this.updateReviewShippingUrl = options.updateReviewShippingUrl;
			this.updateReviewPaymentUrl = options.updateReviewPaymentUrl;
			this.isCustomerLoggedIn = options.isCustomerLoggedIn;
//			this.loadingPaymentMethod = options.loadingPaymentMethod;
			this.removeShippingAddress = options.removeShippingAddress;
			this.removeShippingMethod = options.removeShippingMethod;
			this.removePayment = options.removePayment;
		},

		addressFormObserver: function()
		{
			var $this = this;
			$(this.billingFormId).on('change', this.billingFormUpdater, function(event) {
				billing.updatePaymentMethods($this.updatePaymentMethodUrl);
//                checkout.setPayment();
//                if ($this.isShippingSameAsBilling()) {
//                    shipping.updateShippingMethods($this.updateShippingMethodUrl);
//                }
			});

			$(this.shippingFormId).on('change', this.shippingFormUpdater, function(event) {
//                billing.save();
				shipping.updateShippingMethods($this.updateShippingMethodUrl);
//                checkout.setShippingMethod();
//				billing.updatePaymentMethods($this.updatePaymentMethodUrl);
			});

			$(this.shippingMethodFormId).on('change', this.shippingMethodFormUpdater, function(event) {
				shippingMethod.updateReview($this.updateReviewShippingUrl);
//                checkout.setReview();
			});

			$(this.paymentFormId).on('change', this.paymentFormUpdater, function(event) {
				payment.updateReview($this.updateReviewPaymentUrl);
//                checkout.setReview();
			});

//            checkout.setBilling();
//            checkout.setShipping();
//            checkout.setShippingMethod();
//            checkout.setPayment();
		},

		useForShipping: function($this/*, opt*/)
		{
//            $(opt.selector).checked = opt.checked;
			if (!$($this).checked) {
				$(this.useForShippingId).style.display = 'block';
			}
			else {
				$(this.useForShippingId).style.display = 'none';
			}
		},

		selectPaymentMethod: function()
		{
			var elements = Form.getElements(payment.form);
			var i = 1;
			$(elements).each(function(element) {
				if (i == 1) {
					element.checked = true;
				}
				if (element.value == payment.currentMethod) {
					element.checked = true;
//					element.checked = true;
				}
				i++;
			});
			//for (var i=0; i<elements.length; i++) {
//				if (elements.value == payment.currentMethod) {
//					elements.enable();
//					return;
//				}
//			}
		},

		selectShippingMethod: function()
		{
			var elements = Form.getElements(shippingMethod.form);
			var i = 1;
			$(elements).each(function(element) {
				if (i == 1) {
					element.checked = true;
				}
				if (element.value == shippingMethod.currentMethod) {
					element.checked = true;
//                    element.checked = true;
				}
				i++;
			});
		},

//		saveShippingMethod: function()
//		{
//			shippingMethod.save();
//		},

		register: function($this)
		{
			if ($($this).checked) {
				$(this.registerPasswordWrapperId).style.display = 'block';
			}
			else {
				$(this.registerPasswordWrapperId).style.display = 'none';
			}
			checkout.setMethod();
		},

		/*getBaseUrl: function()
		{
			if (this.storeBaseUrl !== null) {
				return this.storeBaseUrl;
			}

			if (!window.location.origin) {
				window.location.origin = window.location.protocol+"//"+window.location.host;
			}
			return window.location.origin;
		},

		getSkinUrl: function()
		{
			return this.skinUrl;
		},*/

		validation: function()
		{
			var billingValidator = new Validation(billing.form);
			var shippingvalidator = new Validation(shipping.form);
			var paymentvalidator = new Validation(payment.form);

			return billingValidator.validate() && shippingvalidator.validate()
				&& shippingMethod.validate() && paymentvalidator.validate() && payment.validate()
		},

		save: function()
		{
			var billingStatus  = billing.save();
			var shippingStatus = shipping.save();
//            var shippingMethodStatus = shippingMethod.save();
//            var paymentStatus = payment.save();

			return billingStatus || shippingStatus/* || shippingMethodStatus || paymentStatus*/;
		},

		/*isShippingSameAsBilling: function ()
		{
			return $$('input:checked[name="billing[use_for_shipping]"]')[0].value === '1';
		},*/

		getIsCustomerLoggedIn: function ()
		{
			return this.isCustomerLoggedIn;
		},

		/*setLoadingPaymentMethod: function(loadingPaymentMethod)
		{
			this.loadingPaymentMethod = loadingPaymentMethod;
		}*/

//        order: {
//            save: function()
//            {
//                var billingValidator = new Validation(billing.form);
//                var shippingvalidator = new Validation(shipping.form);
//                var paymentvalidator = new Validation(payment.form);
//
//                return billingValidator.validate() && shippingvalidator.validate()
//                    && shippingMethod.validate() && paymentvalidator.validate() && payment.validate()
//
////                var billingStatus  = billing.save();
////                var shippingStatus = shipping.save();
////                var shippingMethodStatus = shippingMethod.save();
////                var paymentStatus = payment.save();
////
////                return billingStatus || shippingStatus || shippingMethodStatus || paymentStatus;
//            }
//        }

		/*textFieldDirection: function($this)
		{
			$this.value = $this.value.replace(/\s/g, '');
			var text    = $this.placeholder;

			if (!$this.value || ($this.value === text)) {
				$this.style.direction = 'rtl';
				$this.style.textAlign = 'right';
			}
			else {
				$this.style.direction = 'ltr';
				$this.style.textAlign = 'left';
			}
		}*/

		loginForm: function(showOverlay)
		{
			var condition = showOverlay !== undefined || showOverlay !== 'undefined';
			if ($(this.loginBlockId).hasClassName(this.showBlockClassName)) {
				this.backgroundOverlay(condition ? condition : false);
				$(this.loginBlockId).removeClassName(this.showBlockClassName);
			}
			else {
				this.backgroundOverlay(condition ? condition : true);
				$(this.loginBlockId).addClassName(this.showBlockClassName);
			}
		},

		forgotPasswordForm: function(showOverlay)
		{
			var condition = showOverlay !== undefined || showOverlay !== 'undefined';
			if ($(this.forgotPasswordBlockId).hasClassName(this.showBlockClassName)) {
				this.loginForm(false);
				$(this.forgotPasswordBlockId).removeClassName(this.showBlockClassName);
			}
			else {
				this.loginForm(true);
				$(this.forgotPasswordBlockId).addClassName(this.showBlockClassName);
			}
		},

		closePopup: function(popup)
		{
			$(popup).removeClassName(this.showBlockClassName);
			$(this.backgroundOverlayId).removeClassName(this.showBlockClassName);
		},

		backgroundOverlay: function(show)
		{
			if (show) {
				$(this.backgroundOverlayId).addClassName(this.showBlockClassName);
			}
			else {
				$(this.backgroundOverlayId).removeClassName(this.showBlockClassName);
			}
		}
	};

//	var tid = setInterval(function () {
//		if (document.readyState !== 'complete') return;
//		clearInterval(tid);
	Event.observe(window, 'load', function(){

		// do your work ==============
		if (!Codnitive_OSC.getIsCustomerLoggedIn()) {
			checkout.setMethod();
		}
//		billing.updatePaymentMethods(Codnitive_OSC.updatePaymentMethodUrl);
		shipping.updateShippingMethods(Codnitive_OSC.updateShippingMethodUrl);
		Codnitive_OSC.addressFormObserver();
	});
//	}, 500 );

	/*$(window).bind('scroll', function() {
		  if ($(window).scrollTop() > 80) {
			 $('.topNav').addClass('fixed');
			 $('.categories>ul').css('paddingTop','9px');
			 $('.basket').css('top','0');
		  }
		  else {
			 $('.topNav').removeClass('fixed');
			 $('.categories>ul').css('paddingTop','5px');
			 $('.basket').css('top','-7px');
		  }
	});
	$(document).ready(function() {
		  $('.eachItem').click(function(){
				$('.tabItem').removeClass('selectedSlide');
				$('.tabContent').css('display','none');
				$(this).find('.tabContent').fadeIn('slow');
				$(this).find('.tabItem').addClass('selectedSlide');
		  });
	});

	$(function() {
		  $('#mainContentSlider').carouFredSel({
				auto: {
				   pauseOnHover: 'resume'
				},
				items: 3,
				prev: '#prev',
				next: '#next',
				scroll: {
				   duration : 1000,
				   fx : "slide"
				},
				swipe: {
				   onMouse: true,
				   onTouch: true
				}
		  });

	});*/
//})(jQuery);

/**
 * jQuery wrapper
 */
( function( $ ) {
	var Misc = new Misc();
	var Nav = new Nav();
	
	/**
	 * handels all the miscellaneous design stuff
	 */
	function Misc() {
		
		var self = this;
		
		/*
		 * sets the correct width of the footer menu
		 */
		this.setFooterMenuWidth = function setFooterMenuWidth() {
			var $area, totalWidth, menuWidth, minWidth, usedWidth = 0;
			
			$area          = $( '.footer-widget-area' );
			minWidth       = $area.find( 'aside.widget' ).css( 'min-width' );
			totalWidth     = $area.width();
			
			$area.find( 'aside.widget' ).not( '.widget_nav_menu' ).each(function() {
				usedWidth += $( this ).width();
			});
			
			// if there is not enough space for all elements
			if ( minWidth > totalWidth - usedWidth ) {
				// set menu to min-width
				menuWidth = minWidth;
			} else { // if there is some free space
				// give all the free space to the menu
				menuWidth = totalWidth - usedWidth;
			}
			
			$area.find( '.widget_nav_menu' ).width( menuWidth );
		};
		
		/**
		 * detect if your device uses a touchscreen
		 * 
		 * @author Aamir Shahzad
		 * 
		 * @see http://aamirshahzad.net/detect-touch-screen-with-javascript/
		 * 
		 * @return bool true for touchscreen, else false
		 */
		this.isTouchDevice = function isTouchDevice() {
			return true == ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch);
		};
	}
	
	/**
	 * handels all the navigation stuff
	 */
	function Nav() {
		
		var self = this;
		
		/*
		 * add click events to mobile nav
		 */
		this.initiateMobileEvents = function initiateMobileEvents() {
			// toggle button
			$( 'div#mobile-nav-toggle' ).click(function() {
				$( 'nav#side-menu' ).animate( { width: 'toggle' } );
			} );
			
			// close button
			$( 'div#close-side-menu' ).click(function() {
				self.hideMobile();
			} );
			
			//menu item with children
			$( 'nav#side-menu ul.menu li.menu-item-has-children > a' ).click(function( event ) {
				self.handleMobileItemsWithChildren( this, event );
			} );
		};
		
		/*
		 * open submenu on first click, go to link on second click
		 */
		this.handleMobileItemsWithChildren = function handleMobileItemsWithChildren( el, event ) {
			var $sub_menu = $( el ).parent().children( 'ul.sub-menu' );
			
			// if the child menu items are visible
			if ( $sub_menu.hasClass( 'show-children' ) ) {
				// go to page (restore default behavior)
				$( el ).click(function() {
					location.href = $( this ).attr( 'href' );
					return true;
				});
			} else {
				// dont follow the link (desable default behavior)
				event.preventDefault();
				// if the child menu items are not yet visible
				// show them
				$sub_menu
					.slideToggle()
					.addClass( 'show-children' );
				// and dont follow the link (desable default behavior)
				return false;
			}
		};
		
		/*
		 * hide mobile nav
		 */
		this.hideMobile = function hideMobile() {
			$( 'nav#side-menu' ).hide();
		};
		
		/*
		 * display mobile nav, if there is not enough space for the desktop nav or if its a touch device
		 */
		this.showMobileIfNedded = function showMobileIfNedded() {
			// if its a touch device, always show mobile nav
			if ( Misc.isTouchDevice() ) {
				// hide search form
				$( '#header-search-form' ).hide();
				// show mobile nav
				self.showMobile();
				return; //BREAKPOINT
			}
			
			var $firstLevelItems = $( '.main-navigation ul#primary-menu > li' ),
				$nav             = $( 'nav.main-navigation' ),
				usedSpace        = 0;
			
			// show desktop nav to make it measurable
			if ( false == $nav.is( ':visible') ) {
				$nav.css( 'visibility', 'hidden' ).show();
			}
			
			// add up all the space needed to display all first level nav items
			$firstLevelItems.each( function() {
				usedSpace += $( this ).outerWidth();
			} );
			
			// if content-container is bigger than the used space of the first level nav items
			if ( $( 'div#content' ).width() >= usedSpace ) {
				self.showDesktop();
			} else { // if there is not enough space hide desktop nav and show the mobile nav
				self.showMobile();
			}
			
		};
		
		/*
		 * diplay desktop nav, hide mobile nav
		 */
		this.showDesktop = function showDesktop() {
			// show desktop nav
			$( 'nav.main-navigation' ).css( 'visibility', 'visible' ).show();
			// hide mobile nav toggle button
			$( 'div#mobile-nav-toggle' ).hide();
			// hide mobile nav if open
			self.hideMobile();
			// show meta nav
			$( '#meta-navigation' ).show();
			// show language switch
			$( '#language-switch' ).show();
		};
		
		/*
		 * display mobile nav, hide desktop nav 
		 */
		this.showMobile = function showMobile() {
			$( 'nav.main-navigation' ).css( 'visibility', 'hidden' ).hide();
			$( 'div#mobile-nav-toggle' ).show();
			// hide meta nav
			$( '#meta-navigation' ).hide();
			// hide language switch
			$( '#language-switch' ).hide();
		};
		
		/*
		 * always show the current menu items
		 */
		this.showCurrentTree = function showCurrentTree() {
			$( 'nav#side-menu' )
				.find( '.current-menu-ancestor, .current-menu-item' ).parent( 'ul' )
					.show()
					.addClass( 'show-children' );
		};
	}
	/**
	 * fires after DOM is loaded
	 */
	$( document ).ready(function() {
		Misc.setFooterMenuWidth();
		Nav.showMobileIfNedded();
		Nav.initiateMobileEvents();
		Nav.showCurrentTree();
	});
	
	/**
	 * fires on resizeing of the window
	 */
	jQuery( window ).resize( function() {
		Misc.setFooterMenuWidth();
		Nav.showMobileIfNedded();
	});
	
} )( jQuery );
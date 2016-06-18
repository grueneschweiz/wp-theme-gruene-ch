/**
 * jQuery wrapper
 */
( function( $ ) {
	var Misc     = new Misc();
	var Nav      = new Nav();
     var Campaign = new Campaign();
	
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
           * adjusts the hight of the header text container
           */
          this.setHeaderTextHight = function setHeaderTextHight() {
               var height,
                   width,
                   $line1       = $( '.gruene-header-text-line1:visible' ),
                   $line2       = $( '.gruene-header-text-line2:visible' ),
                   $inner_div   = $( '.gruene-bars-inner-div:visible' ),
                   $header_text = $( '#header-text' );
               
               $inner_div.css( 'width', 'auto' );
               
               width = $line1.outerWidth() > $line2.outerWidth() ? $line1.outerWidth() : $line2.outerWidth();
               
               $inner_div.width( width + 1 );
               
               height = $line1.height() * 2.3 
                       + Math.sin( 5 * Math.PI / 180 ) * width
                       + parseInt( $header_text.css( 'margin-top' ) )
                       + parseInt( $header_text.css( 'margin-bottom' ) )
                       + parseInt( $header_text.css( 'padding-top' ) )
                       + parseInt( $header_text.css( 'padding-bottom' ) );
               
               $header_text.height( height );
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
				$( 'nav#side-menu' ).animate( { width: 'toggle' } );
			} );
			
			//menu item with children
			$( 'nav#side-menu ul.menu li.menu-item-has-children > a' ).click(function( event ) {
				self.handleHideNshowItemsWithChildren( this, event );
			} );
		};
		
		/*
		 * open submenu on first click, go to link on second click
		 */
		this.handleHideNshowItemsWithChildren = function handleHideNshowItemsWithChildren( el, event ) {
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
		
		/*
		 * make footer menu extendable (hide'n'show)
		 */
		this.initiateFooterHideNshow = function initiateFooterHideNshow() {
			// hide children
			$( 'div.footer-widget-area ul.menu li.menu-item-has-children > ul' ).hide();
			
			//menu item with children
			$( 'div.footer-widget-area ul.menu li.menu-item-has-children > a' ).click(function( event ) {
				self.handleHideNshowItemsWithChildren( this, event );
			} );
		};
	}
     
     /**
      * Handels all the campaign stuff
      * 
      * @since 2.0.0
      */
     function Campaign() {
		
		var self = this;
		
		/*
		 * start up campaign dialog
		 */
		this.init = function init() {
               // exit if no dialog
               if ( 0 === $( '.gruene-campaign' ).length ) return; //BREAKPOINT
               
               var layout_max_width = 629,
                   margin           = 20,
                   window_width     = $( window ).width(),
                   $html            = $( 'html' );
           
               $( '.gruene-campaign' ).dialog( {
                    modal       : true,
                    resizable   : false,
                    draggable   : false,
                    closeText   : 'x',
                    maxWidth    : window_width - margin,
                    minWidth    : layout_max_width > window_width - margin ? window_width - margin : layout_max_width,
                    dialogClass : 'gruene-campaign-dialog',
                    position    : { my: 'center center', at: 'center center' },
                    open        : function() {
                         // limit size of background document to hide scrollbars
                         $html
                                 .css( 'height', '100%' )
                                 .css( 'width', '100%' )
                                 .css( 'overflow', 'hidden' )
                                 .css( 'position', 'fixed' );
                         
                         // always open dialog with scrollTop 0
                         $( '.gruene-campaign-dialog' ).scrollTop( 0 );
                         
                         // set dialog width
                         self.setDialogWidth();
                         
                         // set dialog height
                         self.setDialogHeight();
                    },
                    close       : function() {
                         $html
                                 .css( 'height', '' )
                                 .css( 'width', '' )
                                 .css( 'overflow', '' )
                                 .css( 'position', '' );
                    }
               } );
          };
          
          /*
		 * Set hight of the dialog
           * 
           * This method is a bugfix for chrome browsers an 4k screens, they
           * don't get along with the css only solution. 
           */
		this.setDialogHeight = function setDialogHeight() {
               // exit if no dialog
               if ( 0 === $( '.gruene-campaign' ).length ) return; //BREAKPOINT
               
               var margin           = 20,
                   content_height   = $( '.gruene-campaign' ).outerHeight( true ) + 1,
                   adminbar_height  = $( '#wpadminbar' ).outerHeight( true ),
                   window_height    = $( window ).height(),
                   viewport_height  = window_height - adminbar_height - margin,
                   dialog_height    = viewport_height < content_height ? viewport_height : content_height,
                   $dialog          = $( '.gruene-campaign' ).dialog( 'instance' ).uiDialog;
                   
                   // set dialog height
                   $dialog.css( 'height', dialog_height );
                   
                   // if content height > dialog height enable scrolling
                   // else disable to hide scrollbars
                   if ( content_height > dialog_height ) {
                        $dialog.css( 'overflow-y', 'scroll' );
                   } else {
                        $dialog.css( 'overflow-y', 'auto' );
                   }
                   
                   // set postition
                   $dialog.css( 'top', function() {
                         return ( window_height - adminbar_height - dialog_height ) / 2 ;
                   } );
          };
          
          /*
		 * Set width of the dialog
           * 
           * This method is basically used when resizing the screen 
           */
		this.setDialogWidth = function setDialogWidth() {
               // exit if no dialog
               if ( 0 === $( '.gruene-campaign' ).length ) return; //BREAKPOINT
               
               var  layout_max_width = 629,
                    margin           = 20,
                    window_width     = $( window ).width(),
                    dialog_width     = window_width - margin < layout_max_width ? window_width - margin : layout_max_width;
           
               $( '.gruene-campaign-dialog' )
                       .css( 'width', dialog_width )
                       .css( 'left', function() {
                              return ( window_width - dialog_width ) / 2;
                       } );
          };
          
          /*
		 * Close dialog
		 */
		this.close = function close() {
               // exit if no dialog
               if ( 0 === $( '.gruene-campaign' ).length ) return; //BREAKPOINT
               
               $( '.gruene-campaign' ).dialog( 'close' );
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
		Nav.initiateFooterHideNshow();
          Misc.setHeaderTextHight();
          Campaign.init();
	});
	
     /**
      * Fires when complete page is fully loaded
      */
     $( window ).load(function() {
          Misc.setHeaderTextHight();
     });
     
	/**
	 * fires on resizeing of the window
	 */
	$( window ).resize( function() {
		Misc.setFooterMenuWidth();
		Nav.showMobileIfNedded();
          Misc.setHeaderTextHight();
          Campaign.setDialogHeight();
          Campaign.setDialogWidth();
	});
	
} )( jQuery );
import React, { useEffect } from 'react';
import Main from './components/Main';

const App = () => {
  useEffect(() => {
    // Handle sidebar menu links (already working)
    $('.js-navbar-vertical-aside-menu-link')
      .off('click')
      .on('click', function (e) {
        e.preventDefault();
        const $li = $(this).closest('li');
        const $submenu = $(this).next('.js-navbar-vertical-aside-submenu');
        $li.toggleClass('active');
        $submenu.slideToggle(200);
      });

    // Handle collapse button (new addition)
    $('.js-navbar-vertical-aside-toggle-invoker')
      .off('click')
      .on('click', function (e) {
        e.preventDefault();
        // Toggle the 'collapsed' class on the sidebar
        $('.js-navbar-vertical-aside').toggleClass('collapsed');
      });
  }, []); // Runs once on component mount

  return <Main />;
};

export default App;
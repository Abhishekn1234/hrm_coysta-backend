// import React from 'react'
import React, { useState, useEffect } from 'react';
import Main from './components/purchase/Main'
const App = () => {
  useEffect(() => {
        $('.js-navbar-vertical-aside-menu-link')
          .off('click')
          .on('click', function (e) {
            e.preventDefault();
            const $li = $(this).closest('li');
            const $submenu = $(this).next('.js-navbar-vertical-aside-submenu');
            $li.toggleClass('active');
            $submenu.slideToggle(200);
          });
      }, []);
  
  return (
    <Main />
  )
}

export default App
// import React from 'react'
import React, { useState, useEffect } from 'react';
import Main from './components/Main'
const App = () => {
useEffect(() => {
  $('.js-navbar-vertical-aside-menu-link')
    .off('click')
    .on('click', function (e) {
      const $submenu = $(this).next('.js-navbar-vertical-aside-submenu');
      if ($submenu.length > 0) {
        console.log('Submenu found, toggling:', this);
        e.preventDefault();
        const $li = $(this).closest('li');
        $li.toggleClass('active');
        $submenu.slideToggle(200);
      } else {
        console.log('No submenu, allowing navigation:', this);
      }
    });
}, []);
  
  return (
    <Main />
  )
}

export default App
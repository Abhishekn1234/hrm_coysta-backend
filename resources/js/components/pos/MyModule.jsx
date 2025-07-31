// import React, { useState, useEffect } from 'react';
// import 'bootstrap/dist/css/bootstrap.min.css';
// import '@fortawesome/fontawesome-free/css/all.min.css';

// import TopBar from "./TopBar";
// import LeadList from "./LeadList";
// import LeadDetailsTabs from "./LeadDetailsTabs";
// import AddLeadModal from './AddLeadModal';
// import axios from "axios";

// function MyModule() {
//   const [selectedLead, setSelectedLead] = useState(null);
//   const [allLeads, setAllLeads] = useState([]);
//   const [refreshLeads, setRefreshLeads] = useState(0); // Trigger for refreshing leads

//   useEffect(() => {
//     $('.js-navbar-vertical-aside-menu-link')
//       .off('click')
//       .on('click', function (e) {
//         e.preventDefault();
//         const $li = $(this).closest('li');
//         const $submenu = $(this).next('.js-navbar-vertical-aside-submenu');
//         $li.toggleClass('active');
//         $submenu.slideToggle(200);
//       });
//   }, []);

//   function handleLeadData(lead) {
//     setSelectedLead(lead);
//     console.log('Selected lead in parent:', lead);
//   }

//   function handleAllLeadsData(leads) {
//     setAllLeads(prev => {
//       const prevStr = JSON.stringify(prev);
//       const newStr = JSON.stringify(leads);
//       return prevStr !== newStr ? leads : prev;
//     });
//   }

//   function triggerRefreshLeads() {
//     setRefreshLeads(prev => prev + 1);
//   }

//   function handleLeadUpdated(updatedLead) {
//     setSelectedLead(updatedLead);
//   }

//   return (
//     <div className="wrapper p-2">
//       <div className="container-fluid">
//         <TopBar leads={allLeads} onLeadSelect={handleLeadData} />
//         <AddLeadModal onLeadAdded={triggerRefreshLeads} />
//         <div className="row mt-2">
//           <div className="col-md-3">
//             <LeadList
//               onLeadSelect={handleLeadData}
//               onAllLeads={handleAllLeadsData}
//               refresh={refreshLeads}
//             />
//           </div>
//           <div className="col-md-6">
//             <LeadDetailsTabs
//               lead={selectedLead}
//               refresh={triggerRefreshLeads}
//               onLeadUpdated={handleLeadUpdated}
//             />
//           </div>
//         </div>
//       </div>
//     </div>
//   );
// }

// export default MyModule;


import React, { useState, useEffect } from 'react';
import $ from 'jquery';
import 'bootstrap/dist/css/bootstrap.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import Main from './Main';

function MyModule() {
  const [selectedLead, setSelectedLead] = useState(null);
  const [allLeads, setAllLeads] = useState([]);
  const [refreshLeads, setRefreshLeads] = useState(0); // Trigger for refreshing leads

  useEffect(() => {
    // Handle menu links
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

    // Handle collapse button
    $('.js-navbar-vertical-aside-toggle-invoker')
      .off('click')
      .on('click', function () {
        console.log('Collapse button clicked');
        const $sidebar = $('.js-navbar-vertical-aside');
        const $contentWrapper = $('.content-wrapper');
        const $icon = $(this).find('i');

        if ($sidebar.length === 0) {
          console.warn('Sidebar element (.js-navbar-vertical-aside) not found in DOM');
          return;
        }

        // Toggle sidebar visibility
        $sidebar.toggleClass('navbar-vertical-aside-hidden');
        console.log('Sidebar class toggled:', $sidebar.hasClass('navbar-vertical-aside-hidden') ? 'Hidden' : 'Visible');

        // Adjust content wrapper and icon
        if ($sidebar.hasClass('navbar-vertical-aside-hidden')) {
          $contentWrapper.css({
            'margin-left': '0',
            'width': '100%'
          });
          $icon.removeClass('tio-clear').addClass('tio-last-page');
        } else {
          $contentWrapper.css({
            'margin-left': '250px',
            'width': 'calc(100% - 250px)'
          });
          $icon.removeClass('tio-last-page').addClass('tio-clear');
        }
      });

    // Initialize external sidebar library (if applicable)
    const sidebar = document.querySelector('.js-navbar-vertical-aside');
    if (sidebar && window.HSNavbarVerticalAside) {
      console.log('Initializing HSNavbarVerticalAside');
      try {
        new window.HSNavbarVerticalAside(sidebar).init();
      } catch (error) {
        console.error('Error initializing HSNavbarVerticalAside:', error);
      }
    } else {
      if (!sidebar) console.warn('Sidebar element (.js-navbar-vertical-aside) not found');
      if (!window.HSNavbarVerticalAside) console.warn('HSNavbarVerticalAside library not loaded');
    }

    // Cleanup event listeners on component unmount
    return () => {
      $('.js-navbar-vertical-aside-menu-link').off('click');
      $('.js-navbar-vertical-aside-toggle-invoker').off('click');
    };
  }, []);

  function handleLeadData(lead) {
    setSelectedLead(lead);
    console.log('Selected lead in parent:', lead);
  }

  function handleAllLeadsData(leads) {
    setAllLeads((prev) => {
      const prevStr = JSON.stringify(prev);
      const newStr = JSON.stringify(leads);
      return prevStr !== newStr ? leads : prev;
    });
  }

  function triggerRefreshLeads() {
    setRefreshLeads((prev) => prev + 1);
  }

  function handleLeadUpdated(updatedLead) {
    setSelectedLead(updatedLead);
  }

  return (
    <div className="wrapper bg-white min-vh-100">
      <div className="content-header border-bottom py-3 px-3 bg-light">
        <h1 className="m-0 text-dark">POS</h1>
      </div>
      <div className="content py-3 px-0">
        <div className="container-fluid">
          <div className="card w-100">
            <div className="card-header">
              <h3 className="card-title">Lazy ERP POS</h3>
            </div>
            <div className="card-body p-0">
              <Main />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default MyModule;
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
import 'bootstrap/dist/css/bootstrap.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

import TopBar from "./TopBar";
import LeadList from "./LeadList";
import LeadDetailsTabs from "./LeadDetailsTabs";
import AddLeadModal from './AddLeadModal';
import axios from "axios";

function MyModule() {
  const [selectedLead, setSelectedLead] = useState(null);
  const [allLeads, setAllLeads] = useState([]);
  const [refreshLeads, setRefreshLeads] = useState(0); // Trigger for refreshing leads

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

  function handleLeadData(lead) {
    setSelectedLead(lead);
    console.log('Selected lead in parent:', lead);
  }

  function handleAllLeadsData(leads) {
    setAllLeads(prev => {
      const prevStr = JSON.stringify(prev);
      const newStr = JSON.stringify(leads);
      return prevStr !== newStr ? leads : prev;
    });
  }

  function triggerRefreshLeads() {
    setRefreshLeads(prev => prev + 1);
  }

  function handleLeadUpdated(updatedLead) {
    setSelectedLead(updatedLead);
  }

  return (
    <div className="wrapper p-2">
      <div className="container-fluid">
        <TopBar leads={allLeads} onLeadSelect={handleLeadData} />
        <AddLeadModal onLeadAdded={triggerRefreshLeads} />
        <div className="row mt-2">
          <div className="col-md-3">
            <LeadList
              onLeadSelect={handleLeadData}
              onAllLeads={handleAllLeadsData}
              refresh={refreshLeads}
              onRefresh={triggerRefreshLeads}
            />
          </div>
          <div className="col-md-6">
            <LeadDetailsTabs
              lead={selectedLead}
              refresh={triggerRefreshLeads}
              onLeadUpdated={handleLeadUpdated}
            />
          </div>
        </div>
      </div>
    </div>
  );
}

export default MyModule;

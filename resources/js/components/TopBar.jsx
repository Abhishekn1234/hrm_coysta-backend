// import React, { useState, useEffect, useRef } from 'react';

// export default function TopBar({ leads, onLeadSelect }) {
//   const [searchTerm, setSearchTerm] = useState('');
//   const [suggestions, setSuggestions] = useState([]);
//   const dropdownRef = useRef(null);

//   const openAddLeadModal = () => {
//     const modalEl = document.getElementById('addLeadModal');
//     if (modalEl) {
//       const modal = new window.bootstrap.Modal(modalEl);
//       modal.show();
//     } else {
//       console.error('Modal element #addLeadModal not found');
//     }
//   };

//   // useEffect(() => {
//   //   try {
//   //     if (!leads) {
//   //       setSuggestions([]);
//   //       return;
//   //     }

//   //     if (searchTerm.trim() === '') {
//   //       setSuggestions([]);
//   //       return;
//   //     }
//   //     console.log("this is search term",searchTerm);
//   //     console.log("leads",leads);

//   //     const matches = leads.filter(lead =>
//   //       lead?.name?.toLowerCase().includes(searchTerm.toLowerCase())
//   //     );

//   //     if (matches.length === 0) {
//   //       setSuggestions([{ name: 'No results found', isPlaceholder: true }]);
//   //     } else {
//   //       setSuggestions(matches.slice(0, 5));
//   //     }
//   //   } catch (error) {
//   //     console.error('Error during search suggestions:', error);
//   //   }
//   // }, [searchTerm, leads]);
//   useEffect(() => {
//       try {
//         if (!leads) {
//           setSuggestions([]);
//           return;
//         }

//         if (searchTerm.trim() === '') {
//           setSuggestions([]);
//           return;
//         }

//         const lowerCaseSearchTerm = searchTerm.toLowerCase();
        
//         const matches = leads.filter(lead => {
//           // Check each field you want to search
//           return (
//             (lead.name && lead.name.toLowerCase().includes(lowerCaseSearchTerm)) ||
//             (lead.email && lead.email.toLowerCase().includes(lowerCaseSearchTerm)) ||
//             (lead.phone && lead.phone.toString().includes(lowerCaseSearchTerm)) ||
//             (lead.company && lead.company.toLowerCase().includes(lowerCaseSearchTerm)) ||
//             (lead.status && lead.status.toLowerCase().includes(lowerCaseSearchTerm)) ||
//             (lead.leadSource && lead.leadSource.toLowerCase().includes(lowerCaseSearchTerm))
//           );
//         });

//         if (matches.length === 0) {
//           setSuggestions([{ name: 'No results found', isPlaceholder: true }]);
//         } else {
//           setSuggestions(matches.slice(0, 5));
//         }
//       } catch (error) {
//         console.error('Error during search suggestions:', error);
//       }
//     }, [searchTerm, leads]);

//   return (
//     <div className="row align-items-start mb-3">
//       <div className="col-md-8">
//         <div className="input-group input-group-sm">
//           <input
//             type="text"
//             className="form-control"
//             placeholder="Search leads..."
//             value={searchTerm}
//             onChange={e => setSearchTerm(e.target.value)}
//             autoComplete="off"
//           />
//           <button
//             className="btn btn-outline-secondary"
//             type="button"
//             onClick={() => {
//               console.log('Search clicked:', searchTerm);
//             }}
//           >
//             <i className="fas fa-search"></i>
//           </button>

//           {/* Uncomment if you want filters
//           <button
//             className="btn btn-outline-secondary dropdown-toggle"
//             type="button"
//             data-bs-toggle="dropdown"
//             aria-expanded="false"
//           >
//             <i className="fas fa-filter"></i>
//           </button>

//           <div
//             className="dropdown-menu dropdown-menu-end p-2 shadow-sm"
//             style={{ minWidth: '200px' }}
//           >
//             <h6 className="dropdown-header text-muted">Status</h6>
//             {['All', 'Hot', 'Warm', 'Cold'].map((status) => (
//               <a
//                 key={status}
//                 className="dropdown-item d-flex align-items-center"
//                 href="#"
//               >
//                 <span
//                   className={`badge bg-${
//                     status === 'All' ? 'secondary' : status.toLowerCase()
//                   } me-2`}
//                 >
//                   &nbsp;
//                 </span>
//                 {status}
//               </a>
//             ))}
//             <div className="dropdown-divider"></div>
//             <h6 className="dropdown-header text-muted">Assigned</h6>
//             <a className="dropdown-item" href="#">
//               <i className="fas fa-user me-2 text-muted"></i> My Leads
//             </a>
//             <a className="dropdown-item" href="#">
//               <i className="fas fa-users me-2 text-muted"></i> Team
//             </a>
//           </div>
//           */}
//         </div>

//         {/* Suggestions List */}
//         {suggestions.length > 0 && (
//           <ul
//             className="list-group mt-1 shadow-sm"
//             style={{ maxHeight: 200, overflowY: 'auto' }}
//             ref={dropdownRef}
//           >
//             {suggestions.map((lead, index) => (
//               <li
//                 key={lead.id || index}
//                 className={`list-group-item list-group-item-action ${
//                   lead.isPlaceholder ? 'text-muted fst-italic' : ''
//                 }`}
//                 onClick={() => {
//                   if (!lead.isPlaceholder) {
//                     console.log(lead.name);
//                     setSearchTerm(lead.name);
//                     setSuggestions([]);
//                     dropdownRef.current?.blur();
//                     setTimeout(() => setSuggestions([]), 50);
//                     onLeadSelect?.(lead);
//                   }
//                 }}
//                 style={{ cursor: lead.isPlaceholder ? 'default' : 'pointer' }}
//               >
//                 {lead.name} {lead.email && !lead.isPlaceholder && `- ${lead.email}`}
//               </li>
//             ))}
//           </ul>
//         )}
//       </div>

//       <div className="col-md-4 text-end">
//         <div className="btn-group" role="group">
//           <button
//             type="button"
//             className="btn btn-primary btn-sm me-2"
//             onClick={openAddLeadModal}
//           >
//             <i className="fas fa-user-plus me-1"></i> Add Lead
//           </button>
//           <button
//             className="btn btn-success btn-sm"
//             onClick={() => window.location.reload()}
//           >
//             <i className="fas fa-sync-alt me-1"></i> Refresh
//           </button>
//         </div>
//       </div>
//     </div>
//   );
// }
import React, { useState, useEffect, useRef } from 'react';

export default function TopBar({ leads, onLeadSelect }) {
  const [searchTerm, setSearchTerm] = useState('');
  const [suggestions, setSuggestions] = useState([]);
  const dropdownRef = useRef(null);

  const openAddLeadModal = () => {
    const modalEl = document.getElementById('addLeadModal');
    if (modalEl) {
      const modal = new window.bootstrap.Modal(modalEl);
      modal.show();
    } else {
      console.error('Modal element #addLeadModal not found');
    }
  };

  useEffect(() => {
    try {
      if (!leads) {
        setSuggestions([]);
        return;
      }

      if (searchTerm.trim() === '') {
        setSuggestions([]);
        return;
      }

      const lowerCaseSearchTerm = searchTerm.toLowerCase();
      
      const matches = leads.map(lead => {
        const matchedFields = [];
        
        if (lead.name && lead.name.toLowerCase().includes(lowerCaseSearchTerm)) {
          matchedFields.push('Name');
        }
        if (lead.email && lead.email.toLowerCase().includes(lowerCaseSearchTerm)) {
          matchedFields.push('Email');
        }
        if (lead.phone && lead.phone.toString().includes(lowerCaseSearchTerm)) {
          matchedFields.push('Phone');
        }
        if (lead.company && lead.company.toLowerCase().includes(lowerCaseSearchTerm)) {
          matchedFields.push('Company');
        }
        if (lead.status && lead.status.toLowerCase().includes(lowerCaseSearchTerm)) {
          matchedFields.push('Status');
        }
        if (lead.leadSource && lead.leadSource.toLowerCase().includes(lowerCaseSearchTerm)) {
          matchedFields.push('Source');
        }

        return {
          ...lead,
          matchedFields,
          isMatch: matchedFields.length > 0
        };
      }).filter(lead => lead.isMatch);

      if (matches.length === 0) {
        setSuggestions([{ name: 'No results found', isPlaceholder: true }]);
      } else {
        setSuggestions(matches.slice(0, 5));
      }
    } catch (error) {
      console.error('Error during search suggestions:', error);
    }
  }, [searchTerm, leads]);

  const getFieldValue = (lead, field) => {
    switch(field.toLowerCase()) {
      case 'name': return lead.name;
      case 'email': return lead.email;
      case 'phone': return lead.phone;
      case 'company': return lead.company;
      case 'status': return lead.status;
      case 'source': return lead.leadSource;
      default: return '';
    }
  };

  return (
    <div className="row align-items-start mb-3">
      <div className="col-md-8">
        <div className="input-group input-group-sm">
          <input
            type="text"
            className="form-control"
            placeholder="Search leads..."
            value={searchTerm}
            onChange={e => setSearchTerm(e.target.value)}
            autoComplete="off"
          />
          <button
            className="btn btn-outline-secondary"
            type="button"
            onClick={() => {
              console.log('Search clicked:', searchTerm);
            }}
          >
            <i className="fas fa-search"></i>
          </button>
        </div>

        {/* Suggestions List */}
        {suggestions.length > 0 && (
          <ul
            className="list-group mt-1 shadow-sm"
            style={{ maxHeight: 200, overflowY: 'auto' }}
            ref={dropdownRef}
          >
            {suggestions.map((lead, index) => (
              <li
                key={lead.id || index}
                className={`list-group-item list-group-item-action ${
                  lead.isPlaceholder ? 'text-muted fst-italic' : ''
                }`}
                onClick={() => {
                  if (!lead.isPlaceholder) {
                    console.log(lead.name);
                    setSearchTerm(lead.name);
                    setSuggestions([]);
                    dropdownRef.current?.blur();
                    setTimeout(() => setSuggestions([]), 50);
                    onLeadSelect?.(lead);
                  }
                }}
                style={{ cursor: lead.isPlaceholder ? 'default' : 'pointer' }}
              >
                {lead.isPlaceholder ? (
                  lead.name
                ) : (
                  <div>
                    <div className="fw-bold">{lead.name}</div>
                    <div className="small text-muted">
                      Matched in: {lead.matchedFields.join(', ')}
                    </div>
                    {lead.matchedFields.includes('Email') && (
                      <div className="small">Email: {lead.email}</div>
                    )}
                    {lead.matchedFields.includes('Phone') && (
                      <div className="small">Phone: {lead.phone}</div>
                    )}
                    {lead.matchedFields.includes('Company') && (
                      <div className="small">Company: {lead.company}</div>
                    )}
                  </div>
                )}
              </li>
            ))}
          </ul>
        )}
      </div>

      <div className="col-md-4 text-end">
        <div className="btn-group" role="group">
          <button
            type="button"
            className="btn btn-primary btn-sm me-2"
            onClick={openAddLeadModal}
          >
            <i className="fas fa-user-plus me-1"></i> Add Lead
          </button>
          <button
            className="btn btn-success btn-sm"
            onClick={() => window.location.reload()}
          >
            <i className="fas fa-sync-alt me-1"></i> Refresh
          </button>
        </div>
      </div>
    </div>
  );
}
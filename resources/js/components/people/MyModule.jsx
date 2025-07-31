import React, { useEffect } from "react";
import {
  BrowserRouter as Router,
  Routes,
  Route,
  useLocation,
  useNavigate,
} from "react-router-dom";
import './my.css';
import Dashboard from "./Dashboard";
import StaffTable from "./StaffTable";
import CustomerCards from "./CustomerCards";
import CustomerTable from "./CustomerTable";
import VendorCards from "./VendorCards";
import VendorTable from "./VendorTable";
import Staffdetail from "./Staffdetail";
import Customerdetail from "./Customerdetail";
import Vendordetail from "./Vendordetail";
import PeopleTabs from "./PeopleTabs";
import Navbar from "./Navbar";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import $ from "jquery"; // Make sure jQuery is installed and imported

function AppRoutes() {
  const location = useLocation();

  useEffect(() => {
    // Sidebar toggle behavior
    $('.js-navbar-vertical-aside-menu-link')
      .off('click')
      .on('click', function (e) {
        e.preventDefault();
        const $li = $(this).closest('li');
        const $submenu = $(this).next('.js-navbar-vertical-aside-submenu');
        $li.toggleClass('active');
        $submenu.slideToggle(200);
      });

    $('.js-navbar-vertical-aside-toggle-invoker')
      .off('click')
      .on('click', function (e) {
        e.preventDefault();
        $('.js-navbar-vertical-aside').toggleClass('collapsed');
      });
  }, [location.pathname]); // Re-run when route changes

  return (
    <div style={{ backgroundColor: "#f8f9fa", minHeight: "100vh", padding: "1.5rem" }}>
      {/* Show PeopleTabs on all pages */}
      <PeopleTabs />

      <Routes>
        {/* Staff Page */}
        <Route
          path="/admin/people"
          element={
            <>
              <Dashboard />
              <br />
              <StaffTable />
            </>
          }
        />

        {/* Customer Page */}
        <Route
          path="/customer"
          element={
            <>
              <CustomerCards />
              <CustomerTable />
            </>
          }
        />

        {/* Vendor Page */}
        <Route
          path="/vendor"
          element={
            <>
            <br/>
              <VendorCards />
              <VendorTable />
            </>
          }
        />

        {/* Detail Pages */}
        <Route path="/staff/:id" element={<Staffdetail />} />
        <Route path="/admin/people/customers/:id" element={<Customerdetail />} />
        <Route path="/admin/people/vendors/:id" element={<Vendordetail />} />
      </Routes>

      <ToastContainer />
    </div>
  );
}

export default function MyModule() {
  return (
    <Router>
      <AppRoutes />
    </Router>
  );
}

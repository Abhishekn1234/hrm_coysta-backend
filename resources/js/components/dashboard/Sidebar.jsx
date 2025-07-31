import React, { useState, useEffect } from "react";
import { Card, Button } from "react-bootstrap";
import { FaSitemap, FaProjectDiagram, FaBars, FaTimes } from "react-icons/fa";

export default function Sidebar() {
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);
  const [isMobile, setIsMobile] = useState(window.innerWidth < 768);

  useEffect(() => {
    const handleResize = () => {
      setIsMobile(window.innerWidth < 768);
    };
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  const sidebarWidth = isMobile ? "min(80vw, 200px)" : "250px";
  const sidebarLeft = isMobile ? "0px" : "259px";
  const toggleLeft = isMobile
    ? isSidebarOpen
      ? "calc(min(80vw, 200px) + 15px)"
      : "15px"
    : isSidebarOpen
    ? "calc(259px + 250px + 15px)"
    : "calc(259px + 15px)";

  return (
    <>
      <Button
        style={{
          position: "fixed",
          bottom: "15px", // changed from top to bottom
          left: toggleLeft,
          zIndex: 1001,
          backgroundColor: "#1a73e8",
          color: "#fff",
          border: "none",
          padding: "8px 12px",
          borderRadius: "6px",
          boxShadow: "0 2px 4px rgba(0,0,0,0.1)",
          transition: "left 0.3s ease-in-out, background-color 0.2s",
        }}
        onMouseOver={(e) => (e.currentTarget.style.backgroundColor = "#1557b0")}
        onMouseOut={(e) => (e.currentTarget.style.backgroundColor = "#1a73e8")}
        onClick={() => setIsSidebarOpen(!isSidebarOpen)}
      >
        {isSidebarOpen ? <FaTimes size={18} /> : <FaBars size={18} />}
      </Button>

      {isSidebarOpen && (
        <Card
          style={{
            position: "fixed",
            top: "0px",
            left: sidebarLeft,
            height: "100vh",
            width: sidebarWidth,
            transform: isSidebarOpen ? "translateX(0)" : `translateX(-${sidebarWidth})`,
            transition: "transform 0.3s ease-in-out",
            zIndex: 1000,
            backgroundColor: "#ffffff",
            boxShadow: "2px 0 8px rgba(0,0,0,0.1)",
            overflowY: "auto",
          }}
        >
          <Card.Body
            style={{
              padding: isMobile ? "15px" : "20px",
              display: "flex",
              flexDirection: "column",
              gap: isMobile ? "10px" : "12px",
            }}
          >
            <Header isMobile={isMobile} />
            <CEO name="Robert Chen" isMobile={isMobile} />
            <Departments isMobile={isMobile} />
            <FullOrgChartButton isMobile={isMobile} />
          </Card.Body>
        </Card>
      )}
    </>
  );
}

const Header = ({ isMobile }) => (
  <div
    style={{
      display: "flex",
      alignItems: "center",
      gap: "8px",
      paddingBottom: "10px",
      borderBottom: "1px solid #e8ecef",
    }}
  >
    <FaSitemap style={{ fontSize: isMobile ? "1.2rem" : "1.4rem", color: "#1a73e8" }} />
    <Card.Title
      style={{
        fontSize: isMobile ? "1rem" : "1.1rem",
        fontWeight: 600,
        color: "#202124",
      }}
    >
      Organization Structure
    </Card.Title>
  </div>
);

const CEO = ({ name, isMobile }) => (
  <div
    style={{
      fontSize: isMobile ? "0.9rem" : "1rem",
      fontWeight: 500,
      padding: isMobile ? "6px 10px" : "8px 12px",
      backgroundColor: "#f5f6fa",
      borderRadius: "4px",
      color: "#202124",
    }}
  >
    CEO: {name}
  </div>
);

const Departments = ({ isMobile }) => (
  <div
    style={{
      display: "flex",
      flexDirection: "column",
      gap: isMobile ? "6px" : "8px",
    }}
  >
    <Department name="Technology" teams={["Engineering", "Product", "IT Operations"]} isMobile={isMobile} />
    <Department name="Sales & Marketing" teams={["Sales", "Marketing", "Customer Success"]} isMobile={isMobile} />
    <Department name="Operations" teams={["HR & Admin", "Finance", "Facilities"]} isMobile={isMobile} />
  </div>
);

const Department = ({ name, teams, isMobile }) => {
  const [isExpanded, setIsExpanded] = useState(false);

  return (
    <div
      style={{
        display: "flex",
        flexDirection: "column",
        gap: "4px",
      }}
    >
      <div
        style={{
          padding: isMobile ? "6px 10px" : "8px 12px",
          backgroundColor:
            name === "Technology"
              ? "#e8f0fe"
              : name === "Sales & Marketing"
              ? "#fce8e6"
              : "#e6f4ea",
          borderRadius: "4px",
          fontWeight: 500,
          color: "#202124",
          cursor: "pointer",
          transition: "background-color 0.2s",
        }}
        onMouseOver={(e) =>
          (e.currentTarget.style.backgroundColor =
            name === "Technology"
              ? "#d2e3fc"
              : name === "Sales & Marketing"
              ? "#fad2cf"
              : "#d7e8dc")
        }
        onMouseOut={(e) =>
          (e.currentTarget.style.backgroundColor =
            name === "Technology"
              ? "#e8f0fe"
              : name === "Sales & Marketing"
              ? "#fce8e6"
              : "#e6f4ea")
        }
        onClick={() => setIsExpanded(!isExpanded)}
      >
        {name} {isExpanded ? "−" : "+"}
      </div>
      {isExpanded && (
        <div
          style={{
            marginLeft: isMobile ? "12px" : "16px",
            display: "flex",
            flexDirection: "column",
            gap: "4px",
          }}
        >
          {teams.map((team, index) => (
            <div
              key={index}
              style={{
                padding: isMobile ? "4px 8px" : "6px 10px",
                backgroundColor: "#f8f9fa",
                borderRadius: "3px",
                fontSize: isMobile ? "0.8rem" : "0.85rem",
                color: "#4a4a4a",
              }}
            >
              {team}
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

const FullOrgChartButton = ({ isMobile }) => (
  <Button
    variant="outline-primary"
    style={{
      marginTop: isMobile ? "10px" : "12px",
      padding: isMobile ? "6px" : "8px",
      fontSize: isMobile ? "0.85rem" : "0.9rem",
      borderColor: "#1a73e8",
      color: "#1a73e8",
      borderRadius: "4px",
      display: "flex",
      alignItems: "center",
      justifyContent: "center",
      transition: "background-color 0.2s, color 0.2s",
    }}
    onMouseOver={(e) => {
      e.currentTarget.style.backgroundColor = "#1a73e8";
      e.currentTarget.style.color = "#fff";
    }}
    onMouseOut={(e) => {
      e.currentTarget.style.backgroundColor = "transparent";
      e.currentTarget.style.color = "#1a73e8";
    }}
  >
    <FaProjectDiagram style={{ marginRight: "6px", fontSize: isMobile ? "0.9rem" : "1rem" }} />
    Full Organization Chart
  </Button>
);

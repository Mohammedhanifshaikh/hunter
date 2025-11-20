import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider, useIsFetching } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route, useLocation } from "react-router-dom";
import { useEffect, useState } from "react";
import Loader from "./components/Loader";
import Navigation from "./components/Navigation";
import Footer from "./components/Footer";
import Home from "./pages/Home";
import About from "./pages/About";
import Services from "./pages/Services";
import Process from "./pages/Process";
import WhyUs from "./pages/WhyUs";
import Timeline from "./pages/Timeline";
import VisionMission from "./pages/VisionMission";
import Contact from "./pages/Contact";
import NotFound from "./pages/NotFound";
import { Button } from "@/components/ui/button";
import { Tooltip, TooltipTrigger, TooltipContent } from "@/components/ui/tooltip";
import whatsappIcon from "@/assets/whatsapp-icon.svg";
import PrivacyPolicy from "./pages/PrivacyPolicy";
import TermsAndConditions from "./pages/TermsAndConditions";

const queryClient = new QueryClient();

const ScrollHandler = () => {
  const location = useLocation();
  useEffect(() => {
    const hash = location.hash;
    if (hash) {
      const id = decodeURIComponent(hash.replace('#', ''));
      const el = document.getElementById(id);
      if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        return;
      }
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }, [location.pathname, location.hash]);
  return null;
};

const LoaderController = () => {
  const location = useLocation();
  const isFetching = useIsFetching();
  const [routeChanging, setRouteChanging] = useState(false);

  useEffect(() => {
    setRouteChanging(true);
    const t = setTimeout(() => setRouteChanging(false), 600);
    return () => clearTimeout(t);
  }, [location.pathname, location.hash]);

  const active = routeChanging || isFetching > 0;
  return <Loader active={active} />;
};

const FloatingCTA = () => {
  const whatsappNumber = "919619977779"; // +91 96199 77779
  return (
    <div className="fixed z-[60] right-4 sm:right-6 bottom-[calc(env(safe-area-inset-bottom)+16px)] sm:bottom-6">
      <Tooltip>
        <TooltipTrigger asChild>
          <a
            href={`https://wa.me/${whatsappNumber}`}
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Chat on WhatsApp"
          >
            <Button
              size="icon"
              className="w-14 h-14 sm:w-16 sm:h-16 p-0 rounded-full bg-accent hover:bg-accent-glow text-white shadow-2xl focus-visible:ring-2 focus-visible:ring-accent/60"
            >
              <img
                src={whatsappIcon}
                alt="WhatsApp"
                className="w-7 h-7 sm:w-8 sm:h-8 filter brightness-0 invert"
              />
            </Button>
          </a>
        </TooltipTrigger>
        <TooltipContent side="left">Chat on WhatsApp</TooltipContent>
      </Tooltip>
    </div>
  );
};

const App = () => (
  <QueryClientProvider client={queryClient}>
    <TooltipProvider>
      <Toaster />
      <Sonner />
      <BrowserRouter>
        <div className="flex flex-col min-h-screen">
          <ScrollHandler />
          <LoaderController />
          <Navigation />
          <div className="flex-1">
            <Routes>
              <Route path="/" element={<Home />} />
              <Route path="/about" element={<About />} />
              <Route path="/services" element={<Services />} />
              <Route path="/process" element={<Process />} />
              <Route path="/why-us" element={<WhyUs />} />
              <Route path="/timeline" element={<Timeline />} />
              <Route path="/vision-mission" element={<VisionMission />} />
              <Route path="/contact" element={<Contact />} />
              <Route path="/privacy-policy" element={<PrivacyPolicy />} />
              <Route path="/terms-and-conditions" element={<TermsAndConditions />} />
              <Route path="*" element={<NotFound />} />
            </Routes>
          </div>
          <Footer />
          <FloatingCTA />
        </div>
      </BrowserRouter>
    </TooltipProvider>
  </QueryClientProvider>
);

export default App;

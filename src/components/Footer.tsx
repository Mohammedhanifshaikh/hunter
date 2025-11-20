import { Link } from "react-router-dom";
import { Mail, Phone, MapPin, Linkedin, Facebook, Instagram } from "lucide-react";
import hunterLogo from "@/assets/hunter-logo.svg";

const Footer = () => {
  return (
    <footer className="bg-primary text-primary-foreground relative overflow-hidden">
      {/* Decorative Wave Pattern */}
      <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent via-accent-glow to-accent"></div>
      
      <div className="container mx-auto px-4 py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
          {/* Company Info */}
          <div>
            <img src={hunterLogo} alt="Hunter Logistics" className="h-24 sm:h-28 lg:h-32 w-auto mb-6" />
            <p className="text-sm text-primary-foreground/80 mb-4">
              Delivering Trust, On Time, Every Time.
            </p>
            <div className="flex space-x-4">
              <a
                href="https://linkedin.com"
                target="_blank"
                rel="noopener noreferrer"
                className="text-primary-foreground/80 hover:text-accent transition-colors"
              >
                <Linkedin size={20} />
              </a>
              <a
                href="https://facebook.com"
                target="_blank"
                rel="noopener noreferrer"
                className="text-primary-foreground/80 hover:text-accent transition-colors"
              >
                <Facebook size={20} />
              </a>
              <a
                href="https://instagram.com"
                target="_blank"
                rel="noopener noreferrer"
                className="text-primary-foreground/80 hover:text-accent transition-colors"
              >
                <Instagram size={20} />
              </a>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h3 className="text-lg font-semibold mb-4">Quick Links</h3>
            <ul className="space-y-2">
              <li>
                <Link to="/about" className="text-sm text-primary-foreground/80 hover:text-accent transition-colors">
                  About Us
                </Link>
              </li>
              <li>
                <Link to="/services" className="text-sm text-primary-foreground/80 hover:text-accent transition-colors">
                  Services
                </Link>
              </li>
              <li>
                <Link to="/why-us" className="text-sm text-primary-foreground/80 hover:text-accent transition-colors">
                  Why Choose Us
                </Link>
              </li>
              <li>
                <Link to="/timeline" className="text-sm text-primary-foreground/80 hover:text-accent transition-colors">
                  Our Journey
                </Link>
              </li>
              <li>
                <Link to="/contact#contact-form" className="text-sm text-primary-foreground/80 hover:text-accent transition-colors">
                  Contact Us
                </Link>
              </li>
            </ul>
          </div>

          {/* Services */}
          <div>
            <h3 className="text-lg font-semibold mb-4">Services</h3>
            <ul className="space-y-2">
              <li>
                <Link to="/services#road-transport" className="text-sm text-primary-foreground/80 hover:text-accent transition-colors">
                  Road Transport
                </Link>
              </li>
              <li>
                <Link to="/services#air-freight" className="text-sm text-primary-foreground/80 hover:text-accent transition-colors">
                  Air Freight
                </Link>
              </li>
              <li>
                <Link to="/services#rail-transport" className="text-sm text-primary-foreground/80 hover:text-accent transition-colors">
                  Rail Transport
                </Link>
              </li>
              <li>
                <Link to="/services#warehouse-operations" className="text-sm text-primary-foreground/80 hover:text-accent transition-colors">
                  Warehouse Operations
                </Link>
              </li>
            </ul>
          </div>

          {/* Contact Info */}
          <div>
            <h3 className="text-lg font-semibold mb-4">Contact Us</h3>
            <ul className="space-y-3">
              <li className="flex items-start space-x-3">
                <MapPin size={18} className="mt-1 flex-shrink-0 text-accent" />
                <a
                  href="https://www.google.com/maps?q=4th+Floor+%2F+416+Omega+Business+Park,+Road+No.+33,+Wagle+Industrial+Estate,+Thane+%28W%29,+400604"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="text-sm text-primary-foreground/80 hover:text-accent transition-colors"
                >
                  4th Floor / 416 Omega Business Park,<br />
                  Road No. 33, Wagle Industrial Estate,<br />
                  Thane (W), 400604
                </a>
              </li>
              <li className="flex items-center space-x-3">
                <Phone size={18} className="flex-shrink-0 text-accent" />
                <a
                  href="tel:+919619977779"
                  className="text-sm text-primary-foreground/80 hover:text-accent transition-colors"
                >
                  +91 96199 77779
                </a>
              </li>
              <li className="flex items-center space-x-3">
                <Mail size={18} className="flex-shrink-0 text-accent" />
                <a
                  href="mailto:info@hunterlogistics.in"
                  className="text-sm text-primary-foreground/80 hover:text-accent transition-colors"
                >
                  info@hunterlogistics.in
                </a>
              </li>
            </ul>
          </div>
        </div>

        {/* Copyright */}
        <div className="border-t border-primary-light mt-12 pt-8 text-center space-y-2">
          <p className="text-sm text-primary-foreground/60">
            © {new Date().getFullYear()} Hunter Logistics Pvt. Ltd. All rights reserved.
          </p>
          <p className="text-sm text-primary-foreground/60">
            Developed by{' '}
            <a
              href="https://techvantage.co.in/"
              target="_blank"
              rel="noopener noreferrer"
              className="underline hover:text-accent"
            >
              TechVantage Software
            </a>
          </p>
          <p className="text-sm text-primary-foreground/60">
            <Link to="/about" className="hover:text-accent">About Us</Link>
            <span className="mx-2">|</span>
            <Link to="/privacy-policy" className="hover:text-accent">Privacy Policy</Link>
            <span className="mx-2">|</span>
            <Link to="/terms-and-conditions" className="hover:text-accent">Terms & Conditions</Link>
          </p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;

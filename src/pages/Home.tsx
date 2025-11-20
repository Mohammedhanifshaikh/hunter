import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { 
  Truck, 
  Plane, 
  Train, 
  Warehouse,
  ArrowRight,
  ArrowLeft, 
  CheckCircle2, 
  Users, 
  TrendingUp,
  Shield,
  Clock,
  Award,
  MapPin,
  Package
} from "lucide-react";
import CountUp from "@/components/CountUp";
import heroImage from "@/assets/hero-trucks.jpg";
import warehouseImage from "@/assets/warehouse.jpg";
import airFreightImage from "@/assets/air-freight.jpg";
import railTransportImage from "@/assets/rail-transport.jpg";
import teamImage from "@/assets/team-coordination.jpg";
import heroVideo from "@/assets/hero.mp4";
import heroVideoWebm from "@/assets/hero.webm";
import { useEffect, useMemo, useRef, useState } from "react";

// Brands carousel with seamless infinite scroll
const BrandsSection = () => {
  // Auto-load all logos placed under src/assets/brands using Vite's glob import
  const imported = import.meta.glob("@/assets/brands/*.{svg,png,jpg,jpeg,webp}", {
    eager: true,
    as: "url",
  }) as Record<string, string>;

  const autoBrands: { name: string; src: string }[] = Object.entries(imported).map(([path, url]) => {
    const file = path.split("/").pop() || "";
    const name = file.replace(/\.[^.]+$/, "").replace(/[-_]/g, " ");
    return { name, src: url };
  });

  // Fallback to public/brands if no assets found under src
  const brands: { name: string; src: string }[] =
    autoBrands.length > 0
      ? autoBrands
      : [
          { name: "Brand 1", src: "/brands/brand-1.svg" },
          { name: "Brand 2", src: "/brands/brand-2.svg" },
          { name: "Brand 3", src: "/brands/brand-3.svg" },
          { name: "Brand 4", src: "/brands/brand-4.svg" },
        ];

  // Deduplicate by src (and name as fallback)
  const uniqueBrands = useMemo(() => {
    const seen = new Set<string>();
    return brands.filter(b => {
      const key = `${b.src}|${b.name}`;
      if (seen.has(key)) return false;
      seen.add(key);
      return true;
    });
  }, [brands]);

  const trackRef = useRef<HTMLDivElement | null>(null);
  const wrapperRef = useRef<HTMLDivElement | null>(null);
  const xRef = useRef(0);
  const rafRef = useRef<number | null>(null);
  const lastTsRef = useRef<number | null>(null);
  const speedPxPerSec = 50;

  const few = uniqueBrands.length <= 3;
  // Triple duplicate for seamless loop - ensures no gaps
  const displayBrands = [...uniqueBrands, ...uniqueBrands, ...uniqueBrands];

  // Seamless infinite scroll with proper reset
  useEffect(() => {
    lastTsRef.current = null;
    
    const step = (ts: number) => {
      const track = trackRef.current;
      if (!track) {
        rafRef.current = requestAnimationFrame(step);
        return;
      }
      
      const last = lastTsRef.current ?? ts;
      const dt = (ts - last) / 1000;
      lastTsRef.current = ts;
      
      // Move left continuously
      xRef.current -= speedPxPerSec * dt;
      
      // Get the width of one complete set (1/3 of total since we tripled)
      const totalWidth = track.scrollWidth;
      const oneSetWidth = totalWidth / 3;
      
      // Reset position when we've scrolled one full set
      // This keeps us always in the "middle" set visually
      if (Math.abs(xRef.current) >= oneSetWidth) {
        xRef.current = xRef.current % oneSetWidth;
      }
      
      track.style.transform = `translateX(${xRef.current}px)`;
      rafRef.current = requestAnimationFrame(step);
    };
    
    rafRef.current = requestAnimationFrame(step);
    return () => {
      if (rafRef.current) cancelAnimationFrame(rafRef.current);
    };
  }, [uniqueBrands.length]);

  // Manual controls
  const nudge = (dir: -1 | 1) => {
    xRef.current += dir * 200;
  };

  return (
    <div className="relative">
      <div className="flex items-center justify-center gap-3 md:gap-6">
        <Button
          variant="outline"
          size="icon"
          aria-label="Previous brands"
          onClick={() => nudge(1)}
          className="shrink-0"
        >
          <ArrowLeft className="h-4 w-4" />
        </Button>

        <div
          ref={wrapperRef}
          className={few ? "inline-block overflow-hidden max-w-2xl" : "flex-1 overflow-hidden"}
        >
          <div
            ref={trackRef}
            className={
              few
                ? "flex items-center gap-8 will-change-transform"
                : "flex items-center gap-12 will-change-transform"
            }
            style={{ transform: `translateX(${xRef.current}px)` }}
          >
            {displayBrands.map((brand, idx) => (
              <div key={`${brand.src}-${idx}`} className="flex-shrink-0">
                <div className="h-16 md:h-20 flex items-center justify-center px-2">
                  <img
                    src={brand.src}
                    alt={brand.name}
                    className="max-h-16 md:max-h-20 w-auto object-contain drop-shadow-md"
                    loading="lazy"
                  />
                </div>
              </div>
            ))}
          </div>
        </div>

        <Button
          variant="outline"
          size="icon"
          aria-label="Next brands"
          onClick={() => nudge(-1)}
          className="shrink-0"
        >
          <ArrowRight className="h-4 w-4" />
        </Button>
      </div>
    </div>
  );
}

const Home = () => {
  return (
    <main className="min-h-screen">
      {/* Hero Section - Cinematic */}
      <section className="relative min-h-[70vh] sm:min-h-[75vh] md:h-screen pt-28 sm:pt-32 md:pt-36 pb-16 sm:pb-20 flex items-center justify-center overflow-hidden">
        <div className="absolute inset-0 z-0">
          <video
            className="w-full h-full object-cover pointer-events-none"
            autoPlay
            muted
            loop
            playsInline
            preload="metadata"
            controls={false}
            controlsList="nodownload noplaybackrate nofullscreen"
            disablePictureInPicture
            aria-hidden
            tabIndex={-1}
          >
            <source src={heroVideoWebm} type="video/webm" />
            <source src={heroVideo} type="video/mp4" />
          </video>
          <div className="absolute inset-0 bg-gradient-to-r from-primary/98 via-primary/90 to-transparent"></div>
        </div>

        {/* Animated Background Elements */}
        <div className="absolute inset-0 z-0 opacity-10 hidden sm:block">
          <div className="absolute top-10 left-6 sm:top-16 sm:left-8 w-40 h-40 sm:w-56 sm:h-56 md:w-72 md:h-72 bg-accent rounded-full blur-3xl animate-float"></div>
          <div className="absolute bottom-10 right-6 sm:bottom-16 sm:right-8 w-52 h-52 sm:w-72 sm:h-72 md:w-96 md:h-96 bg-accent-glow rounded-full blur-3xl animate-float" style={{ animationDelay: '2s' }}></div>
        </div>

        {/* Content */}
        <div className="container mx-auto px-4 z-10">
          <div className="max-w-4xl animate-fade-in-up">
            <div className="inline-block bg-accent px-4 py-1.5 sm:px-6 sm:py-2 rounded-full mb-4 sm:mb-6 shadow-xl">
              <p className="text-accent-foreground text-sm sm:text-base font-semibold">Trusted by 500+ Businesses Across India</p>
            </div>
            
            <h1 className="text-4xl sm:text-6xl lg:text-7xl xl:text-8xl font-bold text-[#0b2a5b] mb-4 sm:mb-6 leading-tight">
              Hunter Logistics
            </h1>
            <div className="h-1.5 sm:h-2 w-28 sm:w-40 bg-gradient-to-r from-accent to-accent-glow mb-6 sm:mb-8"></div>
            
            <p className="text-xl sm:text-2xl md:text-3xl lg:text-4xl text-[#0b2a5b] font-semibold mb-4 sm:mb-6">
              The Complete Supply Chain Partner
            </p>
            
            <p className="text-base sm:text-lg md:text-xl text-[#0b2a5b] mb-8 sm:mb-10 md:mb-12 max-w-3xl leading-relaxed">
              Delivering reliability, speed, and safety through comprehensive road, air, and rail 
              logistics solutions across India. Your cargo, our commitment.
            </p>
            
            <div className="flex flex-col sm:flex-row gap-4 sm:gap-6">
              <Button
                asChild
                size="lg"
                className="bg-accent hover:bg-accent-glow text-accent-foreground font-bold text-base sm:text-lg px-6 py-4 sm:px-8 sm:py-5 md:px-10 md:py-7 shadow-xl hover:shadow-2xl transition-all"
              >
                <Link to="/contact" className="w-full sm:w-auto inline-flex justify-center">
                  Get a Free Quote
                  <ArrowRight className="ml-2" size={20} />
                </Link>
              </Button>
              <Button
                asChild
                size="lg"
                variant="outline"
                className="bg-transparent border-2 border-primary-foreground text-primary-foreground hover:bg-primary-foreground hover:text-primary font-bold text-base sm:text-lg px-6 py-4 sm:px-8 sm:py-5 md:px-10 md:py-7 w-full sm:w-auto"
              >
                <Link to="/services" className="w-full sm:w-auto inline-flex justify-center">Explore Services</Link>
              </Button>
            </div>
          </div>
        </div>

        
      </section>

      {/* Statistics Bar */}
      <section className="bg-card py-10 sm:py-12 shadow-xl relative z-10 -mt-6 sm:-mt-12 md:-mt-16 lg:-mt-20">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            {[
              { icon: Award, end: 15, suffix: "+", label: "Years Experience", color: "text-accent" },
              { icon: Truck, end: 300, suffix: "+", label: "Fleet Vehicles", color: "text-primary" },
              { icon: Users, end: 500, suffix: "+", label: "Happy Clients", color: "text-accent" },
              { icon: MapPin, end: 28, suffix: "", label: "States Coverage", color: "text-primary" },
            ].map((stat, index) => (
              <div
                key={index}
                className="text-center group hover:scale-105 transition-transform duration-300 animate-fade-in"
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                <stat.icon className={`${stat.color} mx-auto mb-3 group-hover:scale-110 transition-transform`} size={40} />
                <p className="text-4xl md:text-5xl font-bold text-foreground mb-2">
                  <CountUp end={stat.end} suffix={stat.suffix} />
                </p>
                <p className="text-muted-foreground font-medium">{stat.label}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* About Section */}
      <section className="py-24 bg-background">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div className="relative animate-fade-in">
              <div className="relative rounded-2xl overflow-hidden shadow-2xl">
                <img
                  src={warehouseImage}
                  alt="Hunter Logistics Operations"
                  className="w-full h-[500px] object-cover"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent"></div>
                <div className="absolute bottom-8 left-8 right-8">
                  <p className="text-primary-foreground text-2xl font-bold">State-of-the-art Facilities</p>
                </div>
              </div>
              <div className="absolute -bottom-8 -right-8 bg-accent text-accent-foreground p-8 rounded-2xl shadow-2xl">
                <p className="text-5xl font-bold mb-2">99.8%</p>
                <p className="text-sm">On-Time Delivery</p>
              </div>
            </div>

            <div className="animate-fade-in-up">
              <div className="inline-block bg-accent/10 px-4 py-2 rounded-full mb-4">
                <p className="text-accent font-semibold">Who We Are</p>
              </div>
              <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-6 leading-tight">
                India's Most Trusted Logistics Partner
              </h2>
              <p className="text-lg text-muted-foreground mb-6 leading-relaxed">
                Since 2010, Hunter Logistics has been at the forefront of India's supply chain revolution. 
                We specialize in delivering comprehensive logistics solutions that combine cutting-edge 
                technology with unmatched reliability.
              </p>
              <p className="text-lg text-muted-foreground mb-8 leading-relaxed">
                Our commitment to excellence has made us the preferred choice for businesses across 
                manufacturing, retail, e-commerce, and automotive sectors.
              </p>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                {[
                  "PAN-India Network Coverage",
                  "Real-Time GPS Tracking",
                  "24/7 Customer Support",
                  "Comprehensive Insurance",
                ].map((feature, index) => (
                  <div key={index} className="flex items-center space-x-3">
                    <div className="bg-accent/20 p-2 rounded-lg">
                      <CheckCircle2 className="text-accent" size={20} />
                    </div>
                    <span className="text-foreground font-medium">{feature}</span>
                  </div>
                ))}
              </div>

              <Button
                asChild
                size="lg"
                className="bg-primary hover:bg-primary-dark text-primary-foreground font-semibold"
              >
                <Link to="/about">Learn More About Us</Link>
              </Button>
            </div>
          </div>
        </div>
      </section>

      {/* Services Section - Enhanced */}
      <section className="py-24 bg-muted">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16 animate-fade-in">
            <div className="inline-block bg-accent/10 px-4 py-2 rounded-full mb-4">
              <p className="text-accent font-semibold">Our Services</p>
            </div>
            <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-6">
              Comprehensive Transportation Solutions
            </h2>
            <p className="text-xl text-muted-foreground max-w-3xl mx-auto">
              From road to rail, air to warehouse – we deliver complete logistics solutions 
              tailored to your business needs
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {[
              {
                icon: Truck,
                title: "Road Transport",
                description: "Pan-India coverage with GPS-tracked fleet",
                image: heroImage,
                features: ["Full & Part Loads", "Temperature Control", "Express Delivery"],
              },
              {
                icon: Plane,
                title: "Air Freight",
                description: "Fast delivery for time-critical cargo",
                image: airFreightImage,
                features: ["International", "Customs Support", "Express Service"],
              },
              {
                icon: Train,
                title: "Rail Transport",
                description: "Cost-effective bulk logistics",
                image: railTransportImage,
                features: ["Container Service", "Bulk Cargo", "Eco-Friendly"],
              },
              {
                icon: Warehouse,
                title: "Warehousing",
                description: "Modern storage and distribution",
                image: warehouseImage,
                features: ["Inventory Mgmt", "Cross-Docking", "Pick & Pack"],
              },
            ].map((service, index) => (
              <Card
                key={index}
                className="group overflow-hidden border-0 shadow-lg hover:shadow-2xl transition-all duration-500 animate-fade-in hover:-translate-y-2"
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                <CardContent className="p-0">
                  <div className="relative h-48 overflow-hidden">
                    <img
                      src={service.image}
                      alt={service.title}
                      loading="lazy"
                      className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-primary/90 to-transparent"></div>
                    <div className="absolute bottom-4 left-4">
                      <div className="bg-accent text-accent-foreground p-3 rounded-lg">
                        <service.icon size={28} />
                      </div>
                    </div>
                  </div>
                  <div className="p-6">
                    <h3 className="text-2xl font-bold text-foreground mb-3 group-hover:text-accent transition-colors">
                      {service.title}
                    </h3>
                    <p className="text-muted-foreground mb-4">{service.description}</p>
                    <ul className="space-y-2 mb-4">
                      {service.features.map((feature, idx) => (
                        <li key={idx} className="flex items-center text-sm text-muted-foreground">
                          <div className="w-1.5 h-1.5 bg-accent rounded-full mr-2"></div>
                          {feature}
                        </li>
                      ))}
                    </ul>
                    <Button
                      asChild
                      variant="link"
                      className="text-accent hover:text-accent-glow p-0 font-semibold"
                    >
                      <Link to={`/services#${["road-transport", "air-freight", "rail-transport", "warehouse-operations"][index]}`}>
                        Learn More <ArrowRight className="ml-1" size={16} />
                      </Link>
                    </Button>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Why Choose Us */}
      <section className="py-24 bg-background">
        <div className="container mx-auto px-4">
          <div className="text-center mb-16 animate-fade-in">
            <div className="inline-block bg-accent/10 px-4 py-2 rounded-full mb-4">
              <p className="text-accent font-semibold">Why Hunter Logistics</p>
            </div>
            <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-6">
              Excellence in Every Delivery
            </h2>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {[
              {
                icon: Shield,
                title: "Safe & Secure",
                description: "Comprehensive insurance and professional handling ensure your cargo's safety at every step",
              },
              {
                icon: Clock,
                title: "Always On Time",
                description: "99.8% on-time delivery backed by real-time tracking and proactive management",
              },
              {
                icon: TrendingUp,
                title: "Cost-Effective",
                description: "Optimized routes and competitive pricing with no hidden charges guarantee best value",
              },
            ].map((reason, index) => (
              <Card
                key={index}
                className="text-center p-8 border-0 shadow-lg hover:shadow-xl transition-all duration-300 animate-fade-in"
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                <div className="bg-accent/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                  <reason.icon className="text-accent" size={36} />
                </div>
                <h3 className="text-2xl font-bold text-foreground mb-4">{reason.title}</h3>
                <p className="text-muted-foreground leading-relaxed">{reason.description}</p>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Testimonials */}
      <section className="py-24 bg-primary text-primary-foreground relative overflow-hidden">
        <div className="absolute inset-0 opacity-10">
          <div className="absolute top-0 left-0 w-96 h-96 bg-accent rounded-full blur-3xl"></div>
          <div className="absolute bottom-0 right-0 w-96 h-96 bg-accent-glow rounded-full blur-3xl"></div>
        </div>

        <div className="container mx-auto px-4 relative z-10">
          <div className="text-center mb-16 animate-fade-in">
            <div className="inline-block bg-accent/20 px-4 py-2 rounded-full mb-4 backdrop-blur-sm">
              <p className="text-primary-foreground font-semibold">Client Success Stories</p>
            </div>
            <h2 className="text-4xl md:text-5xl font-bold mb-6">
              What Our Clients Say
            </h2>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {[
              {
                quote: "Hunter Logistics has transformed our supply chain. Their reliability and professionalism are unmatched.",
                author: "Rajesh Kumar",
                company: "Manufacturing Director, AutoParts Inc.",
              },
              {
                quote: "The real-time tracking and 24/7 support have made our operations seamless. Highly recommended!",
                author: "Priya Sharma",
                company: "Operations Head, RetailCo",
              },
              {
                quote: "Cost-effective, reliable, and always on time. Hunter Logistics is our trusted logistics partner.",
                author: "Amit Patel",
                company: "Supply Chain Manager, TechGlobal",
              },
            ].map((testimonial, index) => (
              <Card
                key={index}
                className="bg-card/10 backdrop-blur-sm border border-primary-foreground/20 text-primary-foreground p-8 animate-fade-in transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 hover:bg-card/20 hover:border-accent/40"
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                <div className="text-accent text-5xl mb-4">"</div>
                <p className="text-lg mb-6 leading-relaxed">{testimonial.quote}</p>
                <div className="border-t border-primary-foreground/20 pt-4">
                  <p className="font-bold">{testimonial.author}</p>
                  <p className="text-sm text-primary-foreground/70">{testimonial.company}</p>
                </div>
              </Card>
            ))}
          </div>
        </div>
      </section>

      {/* Client Logos */}
      <section className="py-20 bg-muted">
        <div className="container mx-auto px-4">
          <div className="text-center mb-12">
            <h3 className="text-2xl font-bold text-foreground mb-4">Trusted by Leading Brands</h3>
            <p className="text-muted-foreground">Powering the supply chains of India's top companies</p>
          </div>

          <BrandsSection />
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-24 bg-gradient-to-br from-primary to-primary-light text-primary-foreground relative overflow-hidden">
        <div className="absolute inset-0">
          <img
            src={teamImage}
            alt="Hunter Logistics Team"
            loading="lazy"
            className="w-full h-full object-cover opacity-20"
          />
          <div className="absolute inset-0 bg-gradient-to-r from-primary via-primary/95 to-primary/90"></div>
        </div>

        <div className="container mx-auto px-4 relative z-10 text-center">
          <h2 className="text-4xl md:text-6xl font-bold mb-6 animate-fade-in">
            Ready to Optimize Your Supply Chain?
          </h2>
          <p className="text-xl md:text-2xl text-primary-foreground/90 mb-12 max-w-3xl mx-auto animate-fade-in-up">
            Let's discuss how Hunter Logistics can streamline your operations and 
            deliver results that matter
          </p>
          <div className="flex flex-col sm:flex-row gap-6 justify-center animate-fade-in-up" style={{ animationDelay: '0.2s' }}>
            <Button
              asChild
              size="lg"
              className="bg-accent hover:bg-accent-glow text-accent-foreground font-bold text-lg px-10 py-7 shadow-xl"
            >
              <Link to="/contact#contact-form" className="w-full sm:w-auto inline-flex justify-center">Get Started Today</Link>
            </Button>
            <Button
              asChild
              size="lg"
              variant="outline"
              className="bg-transparent border-2 border-primary-foreground text-primary-foreground hover:bg-primary-foreground hover:text-primary font-bold text-lg px-10 py-7 w-full sm:w-auto"
            >
              <Link to="/services" className="w-full sm:w-auto inline-flex justify-center">View All Services</Link>
            </Button>
          </div>
        </div>
      </section>
    </main>
  );
};

export default Home;

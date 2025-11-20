import { Target, Handshake, Award, Users, TrendingUp, Shield, Clock, MapPin, Package } from "lucide-react";
import CountUp from "@/components/CountUp";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Link } from "react-router-dom";
import warehouseImage from "@/assets/warehouse.jpg";
import teamImage from "@/assets/team-coordination.jpg";
import heroImage from "@/assets/hero-trucks.jpg";

const About = () => {
  return (
    <main className="min-h-screen pt-20">
      {/* Hero Section */}
      <section className="relative py-32 bg-gradient-to-br from-primary via-primary-dark to-primary-light text-primary-foreground overflow-hidden">
        <div className="absolute inset-0 opacity-10">
          <div className="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl animate-float"></div>
          <div className="absolute bottom-0 left-0 w-96 h-96 bg-accent-glow rounded-full blur-3xl animate-float" style={{ animationDelay: '2s' }}></div>
        </div>
        
        <div className="container mx-auto px-4 text-center relative z-10">
          <div className="inline-block bg-accent/20 backdrop-blur-sm px-6 py-2 rounded-full mb-6 border border-accent/30">
            <p className="text-primary-foreground/90 font-medium">15+ Years of Excellence</p>
          </div>
          <h1 className="text-5xl md:text-7xl font-bold mb-8 animate-fade-in leading-tight">
            About Hunter Logistics
          </h1>
          <div className="h-2 w-32 bg-gradient-to-r from-accent to-accent-glow mx-auto mb-8"></div>
          <p className="text-xl md:text-2xl text-primary-foreground/90 max-w-4xl mx-auto animate-fade-in-up leading-relaxed">
            Your trusted supply chain partner, delivering reliability, speed, and safety through
            comprehensive road, air, and rail logistics services across India
          </p>
        </div>
      </section>

      {/* Company Overview */}
      <section className="py-24 bg-background">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-20">
            {/* Image */}
            <div className="relative animate-fade-in">
              <div className="grid grid-cols-2 gap-4">
                <img
                  src={warehouseImage}
                  alt="Hunter Logistics Warehouse"
                  loading="lazy"
                  className="rounded-2xl shadow-2xl col-span-2"
                />
                <img
                  src={teamImage}
                  alt="Hunter Logistics Team"
                  loading="lazy"
                  className="rounded-2xl shadow-xl"
                />
                <img
                  src={heroImage}
                  alt="Hunter Logistics Fleet"
                  loading="lazy"
                  className="rounded-2xl shadow-xl"
                />
              </div>
              <div className="absolute -bottom-8 -right-8 bg-accent text-accent-foreground p-8 rounded-2xl shadow-2xl z-10">
                <p className="text-5xl font-bold mb-2"><CountUp end={500} suffix="+" /></p>
                <p className="text-sm font-medium">Happy Clients</p>
              </div>
            </div>

            {/* Content */}
            <div className="animate-fade-in-up">
              <div className="inline-block bg-accent/10 px-4 py-2 rounded-full mb-4">
                <p className="text-accent font-semibold">Our Story</p>
              </div>
              <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-6 leading-tight">
                Your Complete Supply Chain Partner in India
              </h2>
              <p className="text-lg text-muted-foreground mb-6 leading-relaxed">
                Founded in 2010, Hunter Logistics has grown from a regional transport provider to 
                one of India's most trusted logistics companies. Our journey has been marked by 
                unwavering commitment to excellence, innovation, and customer satisfaction.
              </p>
              <p className="text-lg text-muted-foreground mb-6 leading-relaxed">
                We specialize in delivering comprehensive logistics solutions tailored to meet 
                diverse client needs, ensuring seamless operations across road, air, and rail. 
                From small businesses to large enterprises, we power supply chains with precision 
                and reliability.
              </p>
              <p className="text-lg text-muted-foreground mb-8 leading-relaxed">
                With state-of-the-art technology, a dedicated team of logistics experts, and a 
                fleet of 300+ vehicles, we've completed over 50,000 deliveries with a 99.8% 
                on-time success rate.
              </p>

              {/* Key Highlights */}
              <div className="grid grid-cols-2 gap-4">
                {[
                  { icon: MapPin, title: "Pan-India Network", desc: "28 States Coverage" },
                  { icon: Users, title: "Expert Team", desc: "24/7 Support" },
                  { icon: Shield, title: "Fully Insured", desc: "Complete Safety" },
                  { icon: Clock, title: "On-Time", desc: "99.8% Success Rate" },
                ].map((item, index) => (
                  <div key={index} className="flex items-start space-x-3 p-4 bg-muted rounded-xl">
                    <div className="bg-accent/10 p-2 rounded-lg flex-shrink-0">
                      <item.icon className="text-accent" size={20} />
                    </div>
                    <div>
                      <h4 className="font-bold text-foreground mb-1">{item.title}</h4>
                      <p className="text-sm text-muted-foreground">{item.desc}</p>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Core Values */}
          <div className="mt-24">
            <div className="text-center mb-16">
              <div className="inline-block bg-accent/10 px-4 py-2 rounded-full mb-4">
                <p className="text-accent font-semibold">What Drives Us</p>
              </div>
              <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-6">
                Our Core Values
              </h2>
              <p className="text-xl text-muted-foreground max-w-3xl mx-auto">
                These principles guide every decision we make and every delivery we complete
              </p>
              <Button
                asChild
                size="lg"
                className="bg-primary hover:bg-primary-dark text-primary-foreground font-semibold w-full sm:w-auto inline-flex justify-center mt-6"
              >
                <Link to="/contact#contact-form" className="w-full sm:w-auto inline-flex justify-center">Contact Us to Learn More</Link>
              </Button>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              {[
                {
                  icon: Target,
                  title: "Precision & Efficiency",
                  description: "We optimize every route, track every shipment, and deliver with clockwork precision. Our advanced systems ensure zero wastage and maximum efficiency.",
                },
                {
                  icon: Handshake,
                  title: "Trusted Partnerships",
                  description: "We build lasting relationships based on transparency, integrity, and mutual growth. Your success is our success, and we're committed to your long-term goals.",
                },
                {
                  icon: Award,
                  title: "Industry Excellence",
                  description: "Award-winning service backed by ISO certifications and industry recognition. We constantly raise the bar for logistics standards in India.",
                },
              ].map((value, index) => (
                <Card
                  key={index}
                  className="p-8 border-0 shadow-lg hover:shadow-2xl transition-all duration-300 animate-fade-in group hover:-translate-y-2"
                  style={{ animationDelay: `${index * 0.1}s` }}
                >
                  <div className="bg-accent/10 w-16 h-16 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <value.icon className="text-accent" size={32} />
                  </div>
                  <h3 className="text-2xl font-bold text-foreground mb-4">{value.title}</h3>
                  <p className="text-muted-foreground leading-relaxed">{value.description}</p>
                </Card>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Stats Section */}
      <section className="py-24 bg-gradient-to-br from-primary to-primary-light text-primary-foreground relative overflow-hidden">
        <div className="absolute inset-0 opacity-10">
          <div className="absolute top-20 left-20 w-72 h-72 bg-accent rounded-full blur-3xl"></div>
          <div className="absolute bottom-20 right-20 w-96 h-96 bg-accent-glow rounded-full blur-3xl"></div>
        </div>

        <div className="container mx-auto px-4 relative z-10">
          <div className="text-center mb-16">
            <h2 className="text-4xl md:text-5xl font-bold mb-6">Our Impact in Numbers</h2>
            <p className="text-xl text-primary-foreground/90 max-w-3xl mx-auto">
              These metrics reflect our commitment to excellence and customer satisfaction
            </p>
          </div>

          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            {[
              { icon: Award, end: 15, suffix: "+", label: "Years of Excellence", sublabel: "Since 2010", compact: false },
              { icon: Package, end: 50000, suffix: "+", label: "Deliveries Completed", sublabel: "And counting", compact: true },
              { icon: MapPin, end: 28, suffix: "", label: "States Covered", sublabel: "Pan-India reach", compact: false },
              { icon: TrendingUp, end: 99.8, suffix: "%", label: "On-Time Delivery", sublabel: "Consistent performance", compact: false, decimals: 1 },
            ].map((stat, index) => (
              <div
                key={index}
                className="text-center group animate-fade-in"
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                <div className="bg-primary-foreground/10 backdrop-blur-sm w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                  <stat.icon className="text-accent" size={36} />
                </div>
                <p className="text-5xl md:text-6xl font-bold mb-3 group-hover:scale-105 transition-transform">
                  <CountUp end={stat.end} suffix={stat.suffix} durationMs={1800} compact={stat.compact} decimals={(stat as any).decimals || 0} />
                </p>
                <p className="text-xl font-semibold mb-2">{stat.label}</p>
                <p className="text-sm text-primary-foreground/70">{stat.sublabel}</p>
              </div>
            ))}
          </div>
        </div>
      </section>
    </main>
  );
};

export default About;

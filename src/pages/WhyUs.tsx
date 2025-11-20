import { MapPin, Clock, Shield, DollarSign, Users } from "lucide-react";
import CountUp from "@/components/CountUp";
import teamImageNew from "@/assets/team-coordination (1).jpg";

const WhyUs = () => {
  const reasons = [
    {
      icon: MapPin,
      title: "Pan-India Network",
      description: "Extensive reach covering 28 states with strategic hubs for efficient distribution",
    },
    {
      icon: Clock,
      title: "On-Time Delivery",
      description: "99.8% on-time delivery rate backed by real-time tracking and proactive management",
    },
    {
      icon: Shield,
      title: "Safe & Secure",
      description: "Comprehensive insurance coverage and professional handling ensure cargo safety",
    },
    {
      icon: DollarSign,
      title: "Cost-Effective",
      description: "Competitive pricing with no hidden charges and optimized route planning",
    },
    {
      icon: Users,
      title: "Dedicated Team",
      description: "24/7 customer support with experienced logistics professionals at your service",
    },
  ];

  return (
    <main className="min-h-screen pt-20">
      {/* Hero Section */}
      <section className="py-20 bg-gradient-to-br from-primary to-primary-light text-primary-foreground">
        <div className="container mx-auto px-4 text-center">
          <h1 className="text-4xl md:text-6xl font-bold mb-6 animate-fade-in">
            Reliable Logistics, Every Time
          </h1>
          <div className="h-1 w-24 bg-accent mx-auto mb-6"></div>
          <p className="text-xl text-primary-foreground/90 max-w-3xl mx-auto animate-fade-in-up">
            Discover why leading businesses trust Hunter Logistics for their supply chain needs
          </p>
        </div>
      </section>

      {/* Key Benefits */}
      <section className="py-20 bg-background">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-20">
            {/* Checklist */}
            <div className="space-y-6 animate-fade-in">
              <h2 className="text-3xl md:text-4xl font-bold text-foreground mb-8">
                Why Choose Hunter Logistics?
              </h2>
              {reasons.map((reason, index) => (
                <div
                  key={index}
                  className="flex items-start space-x-4 p-6 bg-card rounded-xl shadow-md hover:shadow-lg transition-all duration-300"
                >
                  <div className="bg-accent/10 p-3 rounded-lg flex-shrink-0">
                    <reason.icon className="text-accent" size={24} />
                  </div>
                  <div>
                    <h3 className="text-xl font-bold text-foreground mb-2">
                      {reason.title}
                    </h3>
                    <p className="text-muted-foreground">{reason.description}</p>
                  </div>
                </div>
              ))}
            </div>

            {/* Image Collage */}
            <div className="grid grid-cols-1 gap-6 animate-fade-in-up">
              <div className="relative h-96 md:h-[28rem] lg:h-[32rem] rounded-2xl overflow-hidden shadow-xl">
                <img
                  src={teamImageNew}
                  alt="Our Dedicated Team"
                  loading="lazy"
                  className="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-primary/80 via-primary/20 to-transparent" />
                <div className="absolute bottom-4 left-4 right-4">
                  <h3 className="text-2xl sm:text-3xl font-extrabold text-primary-foreground drop-shadow-md">
                    Our Dedicated Team
                  </h3>
                  <p className="text-primary-foreground/90 text-sm sm:text-base">
                    Expert professionals committed to your logistics success
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Trust Indicators */}
      <section className="py-20 bg-muted">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl md:text-4xl font-bold text-center text-foreground mb-12">
            Trusted by Industry Leaders
          </h2>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
            {[
              { end: 500, suffix: "+", label: "Happy Clients", compact: false },
              { end: 15, suffix: "+", label: "Years Experience", compact: false },
              { end: 50000, suffix: "+", label: "Deliveries", compact: true },
              { end: 28, suffix: "", label: "States Coverage", compact: false },
            ].map((stat, index) => (
              <div
                key={index}
                className="text-center p-6 bg-card rounded-xl shadow-md animate-fade-in"
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                <p className="text-4xl font-bold text-accent mb-2">
                  <CountUp end={stat.end} suffix={stat.suffix} compact={stat.compact} durationMs={1400} />
                </p>
                <p className="text-muted-foreground">{stat.label}</p>
              </div>
            ))}
          </div>
        </div>
      </section>
    </main>
  );
};

export default WhyUs;

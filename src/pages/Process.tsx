import { ClipboardCheck, Package, Truck, CheckCircle2, BarChart3 } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Link } from "react-router-dom";

const Process = () => {
  const steps = [
    {
      icon: ClipboardCheck,
      title: "Booking & Planning",
      description:
        "We analyze your requirements and create a customized logistics plan with optimal routes and timelines.",
    },
    {
      icon: Package,
      title: "Material Handling",
      description:
        "Professional packaging and loading with care, ensuring cargo safety from origin to destination.",
    },
    {
      icon: Truck,
      title: "Transit Management",
      description:
        "Real-time tracking and monitoring throughout the journey with dedicated support teams.",
    },
    {
      icon: CheckCircle2,
      title: "Final Delivery",
      description:
        "Timely delivery with proof of delivery documentation and quality inspection upon arrival.",
    },
    {
      icon: BarChart3,
      title: "Reporting & Improvement",
      description:
        "Comprehensive delivery reports and continuous improvement based on performance analytics.",
    },
  ];

  return (
    <main className="min-h-screen pt-20">
      {/* Hero Section */}
      <section className="py-20 bg-gradient-to-br from-primary to-primary-light text-primary-foreground">
        <div className="container mx-auto px-4 text-center">
          <h1 className="text-4xl md:text-6xl font-bold mb-6 animate-fade-in">
            End-to-End Supply Chain Management
          </h1>
          <div className="h-1 w-24 bg-accent mx-auto mb-6"></div>
          <p className="text-xl text-primary-foreground/90 max-w-3xl mx-auto animate-fade-in-up">
            Our streamlined process ensures seamless logistics from start to finish
          </p>
        </div>
      </section>

      {/* Process Timeline */}
      <section className="py-20 bg-background">
        <div className="container mx-auto px-4">
          <div className="max-w-5xl mx-auto">
            {steps.map((step, index) => (
              <div
                key={index}
                className="relative animate-fade-in"
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                {/* Connector Line */}
                {index < steps.length - 1 && (
                  <div className="hidden md:block absolute left-8 top-20 w-0.5 h-32 bg-accent/30"></div>
                )}

                {/* Step Card */}
                <div className="flex flex-col md:flex-row items-start md:items-center gap-6 mb-12">
                  {/* Icon */}
                  <div className="relative z-10 bg-accent text-accent-foreground w-16 h-16 rounded-full flex items-center justify-center flex-shrink-0 shadow-lg">
                    <step.icon size={28} />
                  </div>

                  {/* Content */}
                  <div className="flex-1 bg-card p-6 md:p-8 rounded-xl shadow-md hover:shadow-xl transition-all duration-300">
                    <div className="flex items-start justify-between mb-3">
                      <h3 className="text-2xl font-bold text-foreground">{step.title}</h3>
                      <span className="text-accent font-bold text-lg">
                        Step {index + 1}
                      </span>
                    </div>
                    <p className="text-muted-foreground text-lg">{step.description}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-muted">
        <div className="container mx-auto px-4 text-center">
          <h2 className="text-3xl md:text-4xl font-bold text-foreground mb-6">
            Experience Seamless Logistics
          </h2>
          <p className="text-xl text-muted-foreground mb-8 max-w-2xl mx-auto">
            Let our proven process deliver results for your business
          </p>
          <div className="flex justify-center">
            <Button
              asChild
              size="lg"
              className="bg-accent hover:bg-accent-glow text-accent-foreground font-semibold"
            >
              <Link to="/contact#contact-form">Let’s Connect</Link>
            </Button>
          </div>
        </div>
      </section>
    </main>
  );
};

export default Process;

import { Truck, Plane, Train, Package } from "lucide-react";
import { Card, CardContent } from "@/components/ui/card";
import airFreightImage from "@/assets/air-freight.jpg";
import railTransportImage from "@/assets/rail-transport.jpg";
import warehouseImage from "@/assets/warehouse.jpg";
import heroTrucksImage from "@/assets/hero-trucks.jpg";

const Services = () => {
  const services = [
    {
      icon: Truck,
      title: "Road Transport",
      description: "Efficient logistics with comprehensive pan-India coverage",
      details: [
        "Full truckload (FTL) and less than truckload (LTL) services",
        "Real-time GPS tracking",
        "Temperature-controlled transport",
        "Dedicated fleet for specialized cargo",
      ],
      image: heroTrucksImage,
    },
    {
      icon: Plane,
      title: "Air Freight",
      description: "Fast delivery for time-critical shipments",
      details: [
        "Express and standard air cargo services",
        "International and domestic air freight",
        "Customs clearance support",
        "Door-to-door delivery options",
      ],
      image: airFreightImage,
    },
    {
      icon: Train,
      title: "Rail Transport",
      description: "Reliable transit for bulk cargo",
      details: [
        "Cost-effective bulk transportation",
        "Container rail services",
        "End-to-end rail logistics",
        "Multimodal integration",
      ],
      image: railTransportImage,
    },
    {
      icon: Package,
      title: "Warehouse Operations",
      description: "Organized efficiency for storage and distribution",
      details: [
        "Modern warehouse facilities",
        "Inventory management systems",
        "Pick and pack services",
        "Cross-docking capabilities",
      ],
      image: warehouseImage,
    },
  ];

  return (
    <main className="min-h-screen pt-20">
      {/* Hero Section */}
      <section className="py-20 bg-gradient-to-br from-primary to-primary-light text-primary-foreground">
        <div className="container mx-auto px-4 text-center">
          <h1 className="text-4xl md:text-6xl font-bold mb-6 animate-fade-in">
            Comprehensive Transportation Solutions
          </h1>
          <div className="h-1 w-24 bg-accent mx-auto mb-6"></div>
          <p className="text-xl text-primary-foreground/90 max-w-3xl mx-auto animate-fade-in-up">
            From road to rail, air to warehouse – we deliver complete logistics solutions
            tailored to your business needs
          </p>
        </div>
      </section>

      {/* Services Grid */}
      <section className="py-20 bg-background">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-1 gap-16">
            {services.map((service, index) => (
              <Card
                key={index}
                className="overflow-hidden border-0 shadow-lg hover:shadow-2xl transition-all duration-300 animate-fade-in scroll-mt-24"
                style={{ animationDelay: `${index * 0.1}s` }}
                id={service.title.toLowerCase().replace(/\s+/g, "-")}
              >
                <CardContent className="p-0">
                  <div
                    className={`grid grid-cols-1 lg:grid-cols-2 gap-0 ${
                      index % 2 === 1 ? "lg:grid-flow-dense" : ""
                    }`}
                  >
                    {/* Image */}
                    <div
                      className={`relative h-80 lg:h-full ${
                        index % 2 === 1 ? "lg:col-start-2" : ""
                      }`}
                    >
                      <img
                        src={service.image}
                        alt={service.title}
                        className="w-full h-full object-cover"
                      />
                      <div className="absolute inset-0 bg-gradient-to-r from-primary/20 to-transparent"></div>
                    </div>

                    {/* Content */}
                    <div className="p-8 lg:p-12 flex flex-col justify-center">
                      <div className="bg-accent/10 w-16 h-16 rounded-lg flex items-center justify-center mb-6">
                        <service.icon className="text-accent" size={32} />
                      </div>
                      <h2 className="text-3xl font-bold text-foreground mb-4">
                        {service.title}
                      </h2>
                      <p className="text-lg text-muted-foreground mb-6">
                        {service.description}
                      </p>
                      <ul className="space-y-3">
                        {service.details.map((detail, idx) => (
                          <li key={idx} className="flex items-start">
                            <div className="bg-accent/20 rounded-full p-1 mr-3 mt-1 flex-shrink-0">
                              <div className="w-2 h-2 bg-accent rounded-full"></div>
                            </div>
                            <span className="text-muted-foreground">{detail}</span>
                          </li>
                        ))}
                      </ul>
                    </div>
                  </div>
                </CardContent>
              </Card>
            ))}
          </div>
        </div>
      </section>
    </main>
  );
};

export default Services;

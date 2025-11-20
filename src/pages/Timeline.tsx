import { Building2, TrendingUp, Laptop, Handshake, Award, Rocket } from "lucide-react";

const Timeline = () => {
  const milestones = [
    {
      year: "2010",
      icon: Building2,
      title: "Company Founded",
      description: "Hunter Logistics established with a vision to revolutionize supply chain management in India",
    },
    {
      year: "2015",
      icon: TrendingUp,
      title: "Pan-India Expansion",
      description: "Expanded operations to cover 28 states with strategic hub locations",
    },
    {
      year: "2018",
      icon: Laptop,
      title: "Digital Transformation",
      description: "Implemented cutting-edge tracking systems and digital logistics platforms",
    },
    {
      year: "2020",
      icon: Handshake,
      title: "Strategic Partnerships",
      description: "Forged partnerships with leading enterprises and international logistics providers",
    },
    {
      year: "2022",
      icon: Award,
      title: "Industry Recognition",
      description: "Awarded for excellence in logistics and supply chain innovation",
    },
    {
      year: "2025",
      icon: Rocket,
      title: "Future Ready",
      description: "Advancing with AI-driven logistics, sustainable practices, and expanded services",
    },
  ];

  return (
    <main className="min-h-screen pt-20">
      {/* Hero Section */}
      <section className="py-20 bg-gradient-to-br from-primary to-primary-light text-primary-foreground">
        <div className="container mx-auto px-4 text-center">
          <h1 className="text-4xl md:text-6xl font-bold mb-6 animate-fade-in">
            Our Growth Journey
          </h1>
          <div className="h-1 w-24 bg-accent mx-auto mb-6"></div>
          <p className="text-xl text-primary-foreground/90 max-w-3xl mx-auto animate-fade-in-up">
            From humble beginnings to becoming India's trusted logistics partner
          </p>
        </div>
      </section>

      {/* Timeline */}
      <section className="py-20 bg-background relative">
        {/* Vertical Line for Desktop */}
        <div className="hidden md:block absolute left-1/2 top-40 bottom-20 w-0.5 bg-accent/30 transform -translate-x-1/2"></div>

        <div className="container mx-auto px-4">
          <div className="max-w-6xl mx-auto">
            {milestones.map((milestone, index) => (
              <div
                key={index}
                className={`relative mb-16 animate-fade-in ${
                  index % 2 === 0 ? "md:text-right" : "md:text-left"
                }`}
                style={{ animationDelay: `${index * 0.15}s` }}
              >
                <div className="flex flex-col md:flex-row items-center gap-6">
                  {/* Left Content (for even indexes on desktop) */}
                  {index % 2 === 0 && (
                    <div className="flex-1 md:pr-12">
                      <div className="bg-card p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <div className="text-accent font-bold text-5xl mb-2">
                          {milestone.year}
                        </div>
                        <h3 className="text-2xl font-bold text-foreground mb-3">
                          {milestone.title}
                        </h3>
                        <p className="text-muted-foreground">{milestone.description}</p>
                      </div>
                    </div>
                  )}

                  {/* Center Icon */}
                  <div className="relative z-10 bg-accent text-accent-foreground w-20 h-20 rounded-full flex items-center justify-center flex-shrink-0 shadow-xl">
                    <milestone.icon size={32} />
                  </div>

                  {/* Right Content (for odd indexes on desktop) */}
                  {index % 2 === 1 && (
                    <div className="flex-1 md:pl-12">
                      <div className="bg-card p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                        <div className="text-accent font-bold text-5xl mb-2">
                          {milestone.year}
                        </div>
                        <h3 className="text-2xl font-bold text-foreground mb-3">
                          {milestone.title}
                        </h3>
                        <p className="text-muted-foreground">{milestone.description}</p>
                      </div>
                    </div>
                  )}

                  {/* Mobile: Always show content below icon */}
                  {index % 2 === 0 && (
                    <div className="md:hidden flex-1 w-full">
                      <div className="bg-card p-8 rounded-xl shadow-lg">
                        <div className="text-accent font-bold text-5xl mb-2">
                          {milestone.year}
                        </div>
                        <h3 className="text-2xl font-bold text-foreground mb-3">
                          {milestone.title}
                        </h3>
                        <p className="text-muted-foreground">{milestone.description}</p>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>
    </main>
  );
};

export default Timeline;

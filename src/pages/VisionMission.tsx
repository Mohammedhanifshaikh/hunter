import { Eye, Target } from "lucide-react";

const VisionMission = () => {
  return (
    <main className="min-h-screen pt-20">
      {/* Hero Section */}
      <section className="py-20 bg-gradient-to-br from-primary to-primary-light text-primary-foreground">
        <div className="container mx-auto px-4 text-center">
          <h1 className="text-4xl md:text-6xl font-bold mb-6 animate-fade-in">
            Driven by Vision, Guided by Purpose
          </h1>
          <div className="h-1 w-24 bg-accent mx-auto mb-6"></div>
          <p className="text-xl text-primary-foreground/90 max-w-3xl mx-auto animate-fade-in-up">
            Our commitment to excellence shapes every decision and delivery
          </p>
        </div>
      </section>

      {/* Vision & Mission */}
      <section className="py-20 bg-background relative overflow-hidden">
        {/* Background decoration */}
        <div className="absolute inset-0 opacity-5">
          <div className="absolute top-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl"></div>
          <div className="absolute bottom-0 left-0 w-96 h-96 bg-primary rounded-full blur-3xl"></div>
        </div>

        <div className="container mx-auto px-4 relative z-10">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
            {/* Vision */}
            <div className="animate-fade-in">
              <div className="bg-card p-10 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 h-full border-t-4 border-accent">
                <div className="bg-accent/10 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                  <Eye className="text-accent" size={32} />
                </div>
                <h2 className="text-3xl font-bold text-foreground mb-6">Our Vision</h2>
                <p className="text-lg text-muted-foreground leading-relaxed mb-6">
                  To be India's most trusted and innovative logistics partner, setting industry
                  benchmarks for reliability, efficiency, and customer satisfaction.
                </p>
                <p className="text-lg text-muted-foreground leading-relaxed">
                  We envision a future where seamless supply chain management empowers businesses
                  to grow without boundaries, supported by cutting-edge technology and sustainable
                  practices.
                </p>
              </div>
            </div>

            {/* Mission */}
            <div className="animate-fade-in" style={{ animationDelay: "0.2s" }}>
              <div className="bg-card p-10 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 h-full border-t-4 border-primary">
                <div className="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mb-6">
                  <Target className="text-primary" size={32} />
                </div>
                <h2 className="text-3xl font-bold text-foreground mb-6">Our Mission</h2>
                <div className="space-y-4">
                  <div className="flex items-start">
                    <div className="bg-primary/20 rounded-full p-1 mr-3 mt-1 flex-shrink-0">
                      <div className="w-2 h-2 bg-primary rounded-full"></div>
                    </div>
                    <p className="text-lg text-muted-foreground leading-relaxed">
                      Deliver exceptional logistics services with unwavering commitment to
                      timeliness and safety
                    </p>
                  </div>
                  <div className="flex items-start">
                    <div className="bg-primary/20 rounded-full p-1 mr-3 mt-1 flex-shrink-0">
                      <div className="w-2 h-2 bg-primary rounded-full"></div>
                    </div>
                    <p className="text-lg text-muted-foreground leading-relaxed">
                      Build lasting partnerships through transparency, integrity, and superior
                      customer service
                    </p>
                  </div>
                  <div className="flex items-start">
                    <div className="bg-primary/20 rounded-full p-1 mr-3 mt-1 flex-shrink-0">
                      <div className="w-2 h-2 bg-primary rounded-full"></div>
                    </div>
                    <p className="text-lg text-muted-foreground leading-relaxed">
                      Continuously innovate with technology and sustainable practices to create
                      value for stakeholders
                    </p>
                  </div>
                  <div className="flex items-start">
                    <div className="bg-primary/20 rounded-full p-1 mr-3 mt-1 flex-shrink-0">
                      <div className="w-2 h-2 bg-primary rounded-full"></div>
                    </div>
                    <p className="text-lg text-muted-foreground leading-relaxed">
                      Empower our team with growth opportunities and foster a culture of
                      excellence
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Values Section */}
      <section className="py-20 bg-muted">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl md:text-4xl font-bold text-center text-foreground mb-12">
            Our Core Values
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            {[
              {
                title: "Reliability",
                description: "Consistent, dependable service you can count on",
              },
              {
                title: "Innovation",
                description: "Embracing technology for smarter logistics",
              },
              {
                title: "Integrity",
                description: "Transparent operations and ethical practices",
              },
            ].map((value, index) => (
              <div
                key={index}
                className="bg-card p-8 rounded-xl shadow-md text-center animate-fade-in transition-all duration-300 hover:shadow-xl hover:-translate-y-2 hover:border hover:border-accent/40 hover:bg-card/95"
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                <h3 className="text-2xl font-bold text-foreground mb-3">{value.title}</h3>
                <p className="text-muted-foreground">{value.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>
    </main>
  );
};

export default VisionMission;

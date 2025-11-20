import { Link, useLocation } from "react-router-dom";
import { useEffect } from "react";
import { Button } from "@/components/ui/button";

const NotFound = () => {
  const location = useLocation();

  useEffect(() => {
    console.error("404 Error: User attempted to access non-existent route:", location.pathname);
  }, [location.pathname]);

  return (
    <main className="relative min-h-[80vh] flex items-center justify-center overflow-hidden">
      <div className="absolute inset-0 bg-gradient-to-b from-primary/10 via-primary/5 to-transparent" />
      <div className="container mx-auto px-4 relative z-10">
        <div className="max-w-2xl mx-auto text-center">
          <h1 className="text-7xl md:text-8xl font-extrabold text-foreground/90 tracking-tight">404</h1>
          <p className="mt-4 text-2xl md:text-3xl font-semibold text-foreground">Page not found</p>
          <p className="mt-3 text-muted-foreground">
            The page you are looking for might have been moved, renamed, or might never have existed.
          </p>

          <div className="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
            <Button asChild size="lg" className="bg-accent hover:bg-accent-glow text-accent-foreground font-semibold px-8">
              <Link to="/">Go to Home</Link>
            </Button>
            <Button asChild variant="outline" size="lg" className="font-semibold px-8">
              <Link to="/contact#contact-form">Contact Us</Link>
            </Button>
          </div>
        </div>
      </div>
    </main>
  );
};

export default NotFound;

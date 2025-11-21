import { useEffect, useRef, useState } from "react";
import { MapPin, Phone, Mail, Send } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { useToast } from "@/hooks/use-toast";
import teamImage from "@/assets/team-coordination.jpg";

const Contact = () => {
  const mapsQuery =
    "4th+Floor+%2F+416+Omega+Business+Park,+Road+No.+33,+Wagle+Industrial+Estate,+Thane+%28W%29,+400604";
  const mapsEmbedUrl = `https://www.google.com/maps?q=${mapsQuery}&output=embed`;
  const mapsLinkUrl = `https://www.google.com/maps?q=${mapsQuery}`;
  const { toast } = useToast();
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    phone: "",
    message: "",
  });
  const [submitting, setSubmitting] = useState(false);
  const captchaContainerRef = useRef<HTMLDivElement | null>(null);
  const widgetIdRef = useRef<number | null>(null);
  const [recaptchaToken, setRecaptchaToken] = useState<string>("");
  const [errors, setErrors] = useState<{ name: string; email: string; phone: string; message: string }>({
    name: "",
    email: "",
    phone: "",
    message: "",
  });
  const siteKey = (window as any).__RECAPTCHA_SITE_KEY__ || "";

  useEffect(() => {
    // Robust render: poll for grecaptcha readiness (script loads async)
    let tries = 0;
    const maxTries = 50; // ~15s at 300ms
    const interval = setInterval(() => {
      // @ts-ignore
      const gre = typeof grecaptcha !== "undefined" ? grecaptcha : null;
      if (!gre || !captchaContainerRef.current || widgetIdRef.current !== null || !siteKey) {
        tries++;
        if (tries >= maxTries || widgetIdRef.current !== null) {
          clearInterval(interval);
        }
        return;
      }
      try {
        // @ts-ignore
        gre.ready(() => {
          try {
            if (!captchaContainerRef.current || widgetIdRef.current !== null) return;
            // @ts-ignore
            widgetIdRef.current = gre.render(captchaContainerRef.current, {
              sitekey: siteKey,
              theme: "light",
              size: "normal",
              callback: (token: string) => {
                try { setRecaptchaToken(token || ""); } catch (_) {}
              },
              'expired-callback': () => {
                try { setRecaptchaToken(""); } catch (_) {}
              },
              'error-callback': () => {
                try { setRecaptchaToken(""); } catch (_) {}
              }
            });
          } catch (_) {}
        });
        clearInterval(interval);
      } catch (_) {
        // ignore and keep polling
      }
    }, 300);

    return () => clearInterval(interval);
  }, [siteKey]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (submitting) return;
    const name = formData.name.trim();
    const email = formData.email.trim();
    const phone = formData.phone.trim();
    const message = formData.message.trim();
    // reset previous errors
    setErrors({ name: "", email: "", phone: "", message: "" });
    if (name.length < 2 || name.length > 80) {
      setErrors((p) => ({ ...p, name: "Name must be between 2–80 characters." }));
      return;
    }
    const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    if (!emailOk) {
      setErrors((p) => ({ ...p, email: "Enter a valid email." }));
      return;
    }
    const phoneOk = /^[0-9+\-()\s]{10,14}$/.test(phone);
    if (!phoneOk) {
      setErrors((p) => ({ ...p, phone: "Enter a valid phone (10–14 digits)." }));
      return;
    }
    if (message.length < 10) {
      setErrors((p) => ({ ...p, message: "Message must be at least 10 characters." }));
      return;
    }
    try {
      setSubmitting(true);
      const fd = new FormData();
      fd.append("fullname", name);
      fd.append("emailaddr", email);
      fd.append("phonenumber", phone);
      fd.append("inquiry", message);
      // Honeypot (should remain empty)
      fd.append("url_field", "");
      // reCAPTCHA v2 checkbox: require a response token
      // @ts-ignore
      const gre = typeof grecaptcha !== "undefined" ? grecaptcha : null;
      // Prefer token captured by widget callback, fallback to grecaptcha.getResponse
      let captchaToken = recaptchaToken || "";
      if (gre) {
        try {
          const tryGet = () => {
            let token = "";
            try {
              if (widgetIdRef.current !== null) {
                // @ts-ignore
                token = gre.getResponse(widgetIdRef.current) || "";
              }
              // @ts-ignore
              if (!token && typeof gre.getResponse === "function") {
                // @ts-ignore
                token = gre.getResponse() || "";
              }
            } catch (_) {}
            return token;
          };

          captchaToken = tryGet();
          if (!captchaToken) {
            // wait briefly for token to populate after checkbox click
            for (let i = 0; i < 10 && !captchaToken; i++) {
              // 100ms * 10 = 1s max wait
              await new Promise((r) => setTimeout(r, 100));
              captchaToken = tryGet();
            }
          }
        } catch (_) {}
      }
      if (!captchaToken) {
        toast({ title: "Please verify", description: "Confirm you are not a robot to proceed." });
        setSubmitting(false);
        return;
      }
      fd.append("g-recaptcha-response", captchaToken);

      const endpoint = `${window.location.origin}/contact-handler.php`;
      const res = await fetch(endpoint, {
        method: "POST",
        body: fd,
      });

      let data: { success?: boolean; message?: string } = {};
      try {
        data = await res.json();
      } catch (_) {}

      if (res.ok && data.success) {
        toast({
          title: "Message sent!",
          description: data.message || "We'll get back to you within 24 hours.",
        });
        setFormData({ name: "", email: "", phone: "", message: "" });
        // Reset reCAPTCHA widget for a new submission
        try {
          // @ts-ignore
          if (typeof grecaptcha !== "undefined" && widgetIdRef.current !== null) {
            // @ts-ignore
            grecaptcha.reset(widgetIdRef.current);
          }
          setRecaptchaToken("");
        } catch (_) { setRecaptchaToken(""); }
      } else {
        toast({
          title: "Failed to send message",
          description: data.message || "Please try again later.",
        });
      }
    } catch (err) {
      toast({
        title: "An error occurred",
        description: "Please check your connection and try again.",
      });
    } finally {
      setSubmitting(false);
    }
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    let v = value;
    if (name === "name") {
      v = v.replace(/[^A-Za-zÀ-ÿ .'-]/g, "");
    } else if (name === "phone") {
      v = v.replace(/[^0-9+\-()\s]/g, "");
    }
    setFormData((prev) => ({
      ...prev,
      [name]: v,
    }));
    // clear field-specific error on change
    if (name === "name" && errors.name) setErrors((p) => ({ ...p, name: "" }));
    if (name === "email" && errors.email) setErrors((p) => ({ ...p, email: "" }));
    if (name === "phone" && errors.phone) setErrors((p) => ({ ...p, phone: "" }));
    if (name === "message" && errors.message) setErrors((p) => ({ ...p, message: "" }));
  };

  return (
    <main className="min-h-screen pt-20">
      {/* Hero Section */}
      <section className="py-20 bg-gradient-to-br from-primary to-primary-light text-primary-foreground">
        <div className="container mx-auto px-4 text-center">
          <h1 className="text-4xl md:text-6xl font-bold mb-6 animate-fade-in">Get in Touch</h1>
          <div className="h-1 w-24 bg-accent mx-auto mb-6"></div>
          <p className="text-xl text-primary-foreground/90 max-w-3xl mx-auto animate-fade-in-up">
            Ready to optimize your supply chain? We're here to help.
          </p>
        </div>
      </section>

      {/* Contact Section */}
      <section id="contact-form" className="py-20 bg-background scroll-mt-24">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
            {/* Form */}
            <div className="animate-fade-in">
              <div className="bg-card/90 backdrop-blur-sm border border-border rounded-2xl shadow-xl p-6 sm:p-8">
                <form onSubmit={handleSubmit} className="space-y-6">
                  {/* Honeypot removed from DOM (handled via FormData url_field), reCAPTCHA v2 checkbox below */}
                  <div className="space-y-2">
                    <label htmlFor="name" className="text-sm font-medium text-foreground">Your Name</label>
                    <Input
                      id="name"
                      name="name"
                      placeholder="John Doe"
                      value={formData.name}
                      onChange={handleChange}
                      required
                      autoComplete="name"
                      inputMode="text"
                      className="h-12 bg-muted/40 border-input focus-visible:ring-2 focus-visible:ring-accent focus-visible:border-accent"
                    />
                    {errors.name && (
                      <Alert variant="destructive" className="mt-2">
                        <AlertDescription>{errors.name}</AlertDescription>
                      </Alert>
                    )}
                  </div>
                  <div className="space-y-2">
                    <label htmlFor="email" className="text-sm font-medium text-foreground">Your Email</label>
                    <Input
                      id="email"
                      name="email"
                      type="email"
                      placeholder="you@company.com"
                      value={formData.email}
                      onChange={handleChange}
                      required
                      className="h-12 bg-muted/40 border-input focus-visible:ring-2 focus-visible:ring-accent focus-visible:border-accent"
                    />
                    {errors.email && (
                      <Alert variant="destructive" className="mt-2">
                        <AlertDescription>{errors.email}</AlertDescription>
                      </Alert>
                    )}
                  </div>
                  <div className="space-y-2">
                    <label htmlFor="phone" className="text-sm font-medium text-foreground">Your Phone</label>
                    <Input
                      id="phone"
                      name="phone"
                      type="tel"
                      placeholder="+91 98765 43210"
                      value={formData.phone}
                      onChange={handleChange}
                      required
                      autoComplete="tel"
                      inputMode="tel"
                      maxLength={14}
                      className="h-12 bg-muted/40 border-input focus-visible:ring-2 focus-visible:ring-accent focus-visible:border-accent"
                    />
                    {errors.phone && (
                      <Alert variant="destructive" className="mt-2">
                        <AlertDescription>{errors.phone}</AlertDescription>
                      </Alert>
                    )}
                  </div>
                  <div className="space-y-2">
                    <label htmlFor="message" className="text-sm font-medium text-foreground">Your Message</label>
                    <Textarea
                      id="message"
                      name="message"
                      placeholder="Tell us about your requirements..."
                      value={formData.message}
                      onChange={handleChange}
                      required
                      rows={6}
                      className="bg-muted/40 border-input focus-visible:ring-2 focus-visible:ring-accent focus-visible:border-accent"
                    />
                    {errors.message && (
                      <Alert variant="destructive" className="mt-2">
                        <AlertDescription>{errors.message}</AlertDescription>
                      </Alert>
                    )}
                  </div>
                  {/* reCAPTCHA v2 checkbox (manual render; no auto-render class/attrs) */}
                  <div className="pt-2">
                    <div ref={captchaContainerRef}></div>
                  </div>
                  <Button
                    type="submit"
                    size="lg"
                    className="w-full bg-accent hover:bg-accent-glow text-accent-foreground font-semibold shadow-lg"
                    disabled={submitting}
                  >
                    <Send className="mr-2" size={20} />
                    {submitting ? "Sending..." : "Send Message"}
                  </Button>
                </form>
              </div>
            </div>

            {/* Contact Info */}
            <div className="space-y-8 animate-fade-in-up">
              {/* Image */}
              <div className="relative h-64 rounded-2xl overflow-hidden shadow-xl">
                <img
                  src={teamImage}
                  alt="Contact Hunter Logistics"
                  className="w-full h-full object-cover"
                />
              </div>

              {/* Contact Details */}
              <div className="space-y-6">
                <div className="flex items-start space-x-4 p-6 bg-card rounded-xl shadow-md border border-transparent transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-accent/40">
                  <div className="bg-accent/10 p-3 rounded-lg flex-shrink-0">
                    <MapPin className="text-accent" size={24} />
                  </div>
                  <div>
                    <h3 className="font-semibold text-foreground mb-2">Address</h3>
                    <a
                      href={mapsLinkUrl}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-muted-foreground hover:text-accent transition-colors"
                    >
                      4th Floor / 416 Omega Business Park,
                      <br />
                      Road No. 33, Wagle Industrial Estate,
                      <br />
                      Thane (W), 400604
                    </a>
                  </div>
                </div>

                <div className="flex items-start space-x-4 p-6 bg-card rounded-xl shadow-md border border-transparent transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-accent/40">
                  <div className="bg-accent/10 p-3 rounded-lg flex-shrink-0">
                    <Phone className="text-accent" size={24} />
                  </div>
                  <div>
                    <h3 className="font-semibold text-foreground mb-2">Phone</h3>
                    <a
                      href="tel:+919619977779"
                      className="text-muted-foreground hover:text-accent transition-colors"
                    >
                      +91 96199 77779
                    </a>
                  </div>
                </div>

                <div className="flex items-start space-x-4 p-6 bg-card rounded-xl shadow-md border border-transparent transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-accent/40">
                  <div className="bg-accent/10 p-3 rounded-lg flex-shrink-0">
                    <Mail className="text-accent" size={24} />
                  </div>
                  <div>
                    <h3 className="font-semibold text-foreground mb-2">Email</h3>
                    <a
                      href="mailto:info@hunterlogistics.in"
                      className="text-muted-foreground hover:text-accent transition-colors"
                    >
                      info@hunterlogistics.in
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Map Section */}
      <section className="py-20 bg-muted">
        <div className="container mx-auto px-4">
          <div className="max-w-6xl mx-auto">
            <h2 className="text-3xl font-bold text-center text-foreground mb-8">Find Us</h2>
            <div className="bg-card p-4 rounded-xl shadow-lg">
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15071.89737019177!2d72.93946490115697!3d19.196322962528203!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7b9f5c38d909d%3A0xbd382394b6bff4f7!2sOmega%20Business%20Park!5e0!3m2!1sen!2sin!4v1761383039248!5m2!1sen!2sin"
                width="100%"
                height="450"
                style={{ border: 0, borderRadius: "8px" }}
                allowFullScreen
                loading="lazy"
                referrerPolicy="no-referrer-when-downgrade"
                title="Hunter Logistics Location"
              ></iframe>
            </div>
          </div>
        </div>
      </section>
    </main>
  );
};

export default Contact;

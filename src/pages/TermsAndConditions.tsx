import { Link } from "react-router-dom";
import {
  Breadcrumb,
  BreadcrumbList,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";

const TermsAndConditions = () => {
  return (
    <main className="min-h-screen pt-20">
      <section className="bg-gradient-to-br from-primary via-primary-dark to-primary-light text-primary-foreground py-16">
        <div className="container mx-auto px-4">
          <Breadcrumb>
            <BreadcrumbList>
              <BreadcrumbItem>
                <BreadcrumbLink asChild>
                  <Link to="/">Home</Link>
                </BreadcrumbLink>
              </BreadcrumbItem>
              <BreadcrumbSeparator />
              <BreadcrumbItem>
                <BreadcrumbPage>Terms & Conditions</BreadcrumbPage>
              </BreadcrumbItem>
            </BreadcrumbList>
          </Breadcrumb>
          <h1 className="text-4xl md:text-5xl font-bold mt-6">Terms & Conditions</h1>
          <p className="mt-3 text-primary-foreground/90 max-w-3xl">Last updated: October 27, 2025</p>
        </div>
      </section>

      <section className="py-16">
        <div className="container mx-auto px-4 space-y-8 max-w-4xl leading-relaxed">
          <p className="text-muted-foreground">Please read these terms and conditions carefully before using Our Service.</p>

          <div>
            <h2 className="text-2xl font-semibold mb-3">Interpretation and Definitions</h2>
            <h3 className="text-xl font-semibold mb-2">Interpretation</h3>
            <p className="text-muted-foreground mb-4">
              The words whose initial letters are capitalized have meanings defined under the following conditions. The
              following definitions shall have the same meaning regardless of whether they appear in singular or in plural.
            </p>
            <h3 className="text-xl font-semibold mb-2">Definitions</h3>
            <p className="text-muted-foreground mb-2">For the purposes of these Terms and Conditions:</p>
            <ul className="list-disc pl-6 space-y-1 text-muted-foreground">
              <li>
                <strong>Affiliate</strong> means an entity that controls, is controlled by, or is under common control with a
                party, where "control" means ownership of 50% or more of the shares, equity interest or other securities
                entitled to vote for election of directors or other managing authority.
              </li>
              <li>
                <strong>Country</strong> refers to: Maharashtra, India
              </li>
              <li>
                <strong>Company</strong> (referred to as either "the Company", "We", "Us" or "Our" in this Agreement)
                refers to hunter logistics pvt. ltd., 4th Floor / 416 Omega Business Park, Road No. 33, Wagle Industrial
                Estate, Thane (W), 400604.
              </li>
              <li>
                <strong>Device</strong> means any device that can access the Service such as a computer, a cell phone or a
                digital tablet.
              </li>
              <li>
                <strong>Service</strong> refers to the Website.
              </li>
              <li>
                <strong>Terms and Conditions</strong> (also referred as "Terms") mean these Terms and Conditions that form the
                entire agreement between You and the Company regarding the use of the Service.
              </li>
              <li>
                <strong>Third-party Social Media Service</strong> means any services or content provided by a third-party that
                may be displayed, included or made available by the Service.
              </li>
              <li>
                <strong>Website</strong> refers to hunterlogistics, accessible from http://www.hunterlogistics.in
              </li>
              <li>
                <strong>You</strong> means the individual accessing or using the Service, or the company, or other legal entity
                on behalf of which such individual is accessing or using the Service, as applicable.
              </li>
            </ul>
          </div>

          <div>
            <h2 className="text-2xl font-semibold mb-3">Acknowledgment</h2>
            <p className="text-muted-foreground">
              These are the Terms and Conditions governing the use of this Service and the agreement that operates between
              You and the Company. Your access to and use of the Service is conditioned on Your acceptance of and compliance
              with these Terms and Conditions. By accessing or using the Service You agree to be bound by these Terms and
              Conditions and our Privacy Policy. You represent that you are over the age of 18.
            </p>
          </div>

          <div>
            <h2 className="text-2xl font-semibold mb-3">Links to Other Websites</h2>
            <p className="text-muted-foreground">
              Our Service may contain links to third-party web sites or services that are not owned or controlled by the
              Company. The Company has no control over, and assumes no responsibility for, the content, privacy policies, or
              practices of any third party web sites or services. We strongly advise You to read the terms and conditions and
              privacy policies of any third-party web sites or services that You visit.
            </p>
          </div>

          <div>
            <h2 className="text-2xl font-semibold mb-3">Termination</h2>
            <p className="text-muted-foreground">
              We may terminate or suspend Your access immediately, without prior notice or liability, for any reason
              whatsoever, including without limitation if You breach these Terms and Conditions. Upon termination, Your right
              to use the Service will cease immediately.
            </p>
          </div>

          <div>
            <h2 className="text-2xl font-semibold mb-3">Limitation of Liability</h2>
            <p className="text-muted-foreground">
              Notwithstanding any damages that You might incur, the entire liability of the Company and any of its suppliers
              under any provision of this Terms and Your exclusive remedy for all of the foregoing shall be limited to the
              amount actually paid by You through the Service or 100 USD if You haven't purchased anything through the
              Service. To the maximum extent permitted by applicable law, in no event shall the Company or its suppliers be
              liable for any special, incidental, indirect, or consequential damages whatsoever.
            </p>
          </div>

          <div>
            <h2 className="text-2xl font-semibold mb-3">"AS IS" and "AS AVAILABLE" Disclaimer</h2>
            <p className="text-muted-foreground">
              The Service is provided to You "AS IS" and "AS AVAILABLE" and with all faults and defects without warranty of
              any kind. The Company expressly disclaims all warranties, whether express, implied, statutory or otherwise,
              including all implied warranties of merchantability, fitness for a particular purpose, title and
              non-infringement. Neither the Company nor any provider makes any representation or warranty that the Service
              will be uninterrupted, error-free, accurate, reliable, free of harmful components, or meet Your requirements.
            </p>
          </div>

          <div>
            <h2 className="text-2xl font-semibold mb-3">Governing Law</h2>
            <p className="text-muted-foreground">
              The laws of the Country, excluding its conflicts of law rules, shall govern these Terms and Your use of the
              Service. Your use of the Application may also be subject to other local, state, national, or international
              laws.
            </p>
          </div>

          <div>
            <h2 className="text-2xl font-semibold mb-3">Disputes Resolution</h2>
            <p className="text-muted-foreground">
              If You have any concern or dispute about the Service, You agree to first try to resolve the dispute informally
              by contacting the Company.
            </p>
          </div>

          <div>
            <h2 className="text-2xl font-semibold mb-3">For European Union (EU) Users</h2>
            <p className="text-muted-foreground">
              If You are a European Union consumer, you will benefit from any mandatory provisions of the law of the country
              in which You are resident.
            </p>
          </div>

          <div>
            <h2 className="text-2xl font-semibold mb-3">United States Legal Compliance</h2>
            <p className="text-muted-foreground">
              You represent and warrant that (i) You are not located in a country that is subject to the United States
              government embargo, or designated as a "terrorist supporting" country, and (ii) You are not listed on any
              United States government list of prohibited or restricted parties.
            </p>
          </div>

          <div>
            <h2 className="text-2xl font-semibold mb-3">Severability and Waiver</h2>
            <h3 className="text-xl font-semibold mb-2">Severability</h3>
            <p className="text-muted-foreground mb-4">
              If any provision of these Terms is held to be unenforceable or invalid, such provision will be changed and
              interpreted to accomplish the objectives of such provision to the greatest extent possible and the remaining
              provisions will continue in full force and effect.
            </p>
            <h3 className="text-xl font-semibold mb-2">Waiver</h3>
            <p className="text-muted-foreground">
              Except as provided herein, the failure to exercise a right or to require performance of an obligation shall
              not affect a party's ability to exercise such right or require such performance at any time thereafter nor
              shall the waiver of a breach constitute a waiver of any subsequent breach.
            </p>
          </div>

          <div>
            <h2 className="text-2xl font-semibold mb-3">Translation Interpretation</h2>
            <p className="text-muted-foreground">
              These Terms and Conditions may have been translated. You agree that the original English text shall prevail in
              the case of a dispute.
            </p>
          </div>

          <div>
            <h2 className="text-2xl font-semibold mb-3">Changes to These Terms and Conditions</h2>
            <p className="text-muted-foreground">
              We reserve the right, at Our sole discretion, to modify or replace these Terms at any time. If a revision is
              material We will make reasonable efforts to provide at least 30 days' notice prior to any new terms taking
              effect. By continuing to access or use Our Service after those revisions become effective, You agree to be
              bound by the revised terms.
            </p>
          </div>

          <div>
            <h2 className="text-2xl font-semibold mb-3">Contact Us</h2>
            <p className="text-muted-foreground">
              If you have any questions about these Terms and Conditions, You can contact us: By email:
              info@hunterlogistics.in • By phone: +91 96199 77779
            </p>
          </div>
        </div>
      </section>
    </main>
  );
};

export default TermsAndConditions;

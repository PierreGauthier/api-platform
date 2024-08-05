import Head from "next/head";
import { useEffect, useState } from "react";

const backUrl = process.env.NEXT_PUBLIC_API_BASE_URL || window.origin

const Admin = () => {
  // Load the admin client-side
  const [DynamicAdmin, setDynamicAdmin] = useState(<p>Loading...</p>);
  useEffect(() => {
    (async () => {
      const HydraAdmin = (await import("@api-platform/admin")).HydraAdmin;

      setDynamicAdmin(<HydraAdmin entrypoint={backUrl}></HydraAdmin>);
    })();
  }, []);

  return (
    <>
      <Head>
        <title>API Platform Admin</title>
      </Head>

      {DynamicAdmin}
    </>
  );
};
export default Admin;

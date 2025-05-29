export default {
  async fetch(request, env, ctx) {
    const url = new URL(request.url);
    const backup = url.searchParams.get("backup");
    const msg = url.searchParams.get("msg");

    // Validate query parameters
    if (!backup || !msg) {
      return new Response(
        JSON.stringify({ error: "Both backup and msg parameters are required" }),
        { status: 400, headers: { "Content-Type": "application/json" } }
      );
    }

    // Build request URL to external API
    const apiUrl = `https://nexus.0-0-0.click/?backup=${encodeURIComponent(backup)}&msg=${encodeURIComponent(msg)}`;

    try {
      const apiResponse = await fetch(apiUrl);
      
      // If the response isn't OK, return a custom error
      if (!apiResponse.ok) {
        return new Response(
          JSON.stringify({
            status: false,
            message: "RESPONSE NOT FOUND, DUE TO ERROR PLEASE TRY AGAIN LATER",
            owner: "@REFLEX_COD3R",
            backupId: backup
          }),
          { status: 500, headers: { "Content-Type": "application/json" } }
        );
      }

      const data = await apiResponse.json();

      // Final response
      const result = {
        status: true,
        message: data.message || "No message",
        owner: "@REFLEX_COD3R",
        backupId: backup,
        chatID: data.id || null
      };

      return new Response(JSON.stringify(result), {
        headers: { "Content-Type": "application/json" }
      });

    } catch (error) {
      // Catch network/JSON errors
      return new Response(
        JSON.stringify({
          status: false,
          message: "Failed to fetch or parse API response",
          error: error.message,
          owner: "@REFLEX_COD3R",
          backupId: backup
        }),
        { status: 500, headers: { "Content-Type": "application/json" } }
      );
    }
  }
};

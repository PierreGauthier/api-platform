vcl 4.0;
import std;

backend default {
    .host = "$BACKEND_HOST";
    .port = "$BACKEND_PORT";
}

sub vcl_recv {
    if (req.method == "PURGE") {
        if (!client.ip ~ purge) {
            return (synth(405, "Not allowed."));
        }
        return (purge);
    }
    if (req.http.upgrade ~ "(?i)websocket") {
        return (pipe);
    }
}

sub vcl_pipe {
    if (req.http.upgrade) {
        set bereq.http.upgrade = req.http.upgrade;
        set bereq.http.connection = req.http.connection;
    }
}

acl purge {
    "localhost";
    "127.0.0.1";
}

sub vcl_backend_response {
    set beresp.ttl = 5m;
    set beresp.grace = 2m;
}

sub vcl_deliver {
    if (obj.hits > 0) {
        set resp.http.X-Cache = "HIT";
    } else {
        set resp.http.X-Cache = "MISS";
    }
}

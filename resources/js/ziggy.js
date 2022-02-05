const Ziggy = {"url":"http:\/\/127.0.0.1:8000","port":null,"defaults":{},"routes":{"dashboard":{"uri":"\/","methods":["GET","HEAD"]},"register":{"uri":"register","methods":["POST"]},"logout":{"uri":"logout","methods":["GET","HEAD"]}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };

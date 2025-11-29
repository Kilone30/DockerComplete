{{flutter_js}}
{{flutter_build_config}}

_flutter.buildConfig.builds[0].mainJsPath += "?v=" + serviceWorkerVersion;

_flutter.loader.load({
    serviceWorkerSettings: {
        serviceWorkerVersion: serviceWorkerVersion
    }
});

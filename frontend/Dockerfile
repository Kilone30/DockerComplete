FROM ubuntu:22.04 as build

RUN apt update && apt install -y curl git unzip xz-utils zip libglu1-mesa

RUN git clone https://github.com/flutter/flutter.git /usr/local/flutter
ENV PATH="/usr/local/flutter/bin:/usr/local/flutter/bin/cache/dart-sdk/bin:${PATH}"

RUN flutter channel stable
RUN flutter upgrade

WORKDIR /app
COPY . .

RUN flutter config --enable-web
RUN flutter build web --release

FROM nginx:stable
COPY --from=build /app/build/web /usr/share/nginx/html
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]

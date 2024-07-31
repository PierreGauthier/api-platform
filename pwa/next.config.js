/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  swcMinify: true,
  output: 'standalone',
  env: {
    API_SERVER_NAME: process.env.API_SERVER_NAME,
  }
}

module.exports = nextConfig

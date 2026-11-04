// Configuration Astro — NuisiVox (nuisivox.fr)
// Pages en .html à la racine (build.format 'file') : les adresses du site ne changent pas.
import { defineConfig } from 'astro/config';

export default defineConfig({
  site: 'https://nuisivox.fr',
  build: { format: 'file', assets: '_astro' },
  trailingSlash: 'ignore',
  compressHTML: false,
  prefetch: false,
  devToolbar: { enabled: false },
});

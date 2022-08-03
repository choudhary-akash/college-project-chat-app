import { createApp, h,  } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import mitt from 'mitt';
import './bootstrap';

const emitter = mitt();

window.ChatStore.emitter = emitter;

createInertiaApp({
  resolve: name => require(`./Pages/${name}`),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
	  .provide('emitter', emitter)
	  .provide('Echo', window.Echo)
      .mount(el)
  },
})
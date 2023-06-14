import * as PusherTypes from 'pusher-js';

declare global {
  interface Window {
    Pusher: PusherTypes;
  }
}

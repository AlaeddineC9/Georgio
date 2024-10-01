import { registerVueControllerComponents } from '@symfony/ux-vue';



// 

import './bootstrap.js';
import './styles/app.css';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import { start } from '@hotwired/turbo';
start();
import { TurboDriveAdapter } from "@symfony/stimulus-bridge";
import { Application } from "stimulus";
import { startStimulusApp } from '@symfony/stimulus-bridge';

const application = startStimulusApp();










registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));
registerVueControllerComponents();
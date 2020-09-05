/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

import App from "./components/App";

require('./bootstrap');

import React from 'react'
import { render } from 'react-dom'
// import store from './store'
// import Routes from './routes'
import {
    BrowserRouter as Router,
    Switch, Route, Link
} from "react-router-dom"
import Home from "./components/Pages/Home";

render((
    <Router>
        <App/>
    </Router>
    ),
    document.getElementById('app'),
)

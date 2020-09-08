/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

import App from "./components/App";

require('./bootstrap');

import React from 'react'
import { render } from 'react-dom'
import {
    BrowserRouter as Router,
} from "react-router-dom"
import {UserProvider} from "./context/user";

render((
    <Router>
        <UserProvider>
            <App/>
        </UserProvider>
    </Router>
    ),
    document.getElementById('app'),
)

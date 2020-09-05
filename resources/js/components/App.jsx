import React from 'react'
import {Route, Switch} from "react-router-dom";
import Home from "./Pages/Home";

const App = () => {
    return (
        <Switch>
            <Route path="/">
                <Home/>
            </Route>
        </Switch>
    )
}

export default App

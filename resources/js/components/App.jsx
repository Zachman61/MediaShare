import React, {useEffect, useContext} from 'react'
import {Route, Switch} from "react-router-dom";
import Home from "./Pages/Home";
import UserContext, {UserProvider} from "../context/user";
import NavBar from "./NavBar";

const App = () => {
    const {userState, dispatchForUser} = useContext(UserContext)
    useEffect(() => {
        axios.get('/api/user')
            .then(response => {
                dispatchForUser({type: 'LOGIN', payload: response.data})
            });
    }, [])
    return (
        <>
            <NavBar {...userState} />
            <Switch>
                <Route path="/">
                    <Home/>
                </Route>
            </Switch>
        </>
    )
}

export default App

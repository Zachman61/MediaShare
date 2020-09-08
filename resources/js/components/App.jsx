import React, {useEffect, useContext} from 'react'
import {Route, Switch} from "react-router-dom";
import Home from "./Pages/Home";
import UserContext from "../context/user";
import NavBar from "./NavBar";
import {MediaProvider} from "../context/media";

const App = () => {
    const {userState, dispatchForUser} = useContext(UserContext)
    useEffect(() => {
        axios.get('/api/user')
            .then(response => {
                dispatchForUser({type: 'LOGIN', payload: response.data})
            })
            .catch(error => {})
    }, [])
    return (
        <>
            <NavBar {...userState} />
            <div className='container'>
                <Switch>
                    <Route path="/">
                        <MediaProvider>
                            <Home {...userState} />
                        </MediaProvider>
                    </Route>
                </Switch>
            </div>
        </>
    )
}

export default App

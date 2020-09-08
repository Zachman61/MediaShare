import React, {useEffect, useContext} from 'react'
import {Route, Switch} from "react-router-dom";
import Home from "./Pages/Home";
import UserContext from "../context/user";
import NavBar from "./NavBar";
import {MediaProvider} from "../context/media";
import MediaView from "./Pages/MediaView";

const App = () => {
    const {userState, dispatchForUser} = useContext(UserContext)
    useEffect(() => {
        axios.get('/api/user')
            .then(response => {
                dispatchForUser({type: 'LOGIN', payload: response.data})
            })
            .catch(error => {
                console.log(error)
            })
    }, [])
    return (
        <>
            <NavBar {...userState} />
            <div className='container'>
                <Switch>
                    <MediaProvider>
                        <Route path='/media/:hash'>
                            <MediaView/>
                        </Route>
                        <Route exact path="/">
                            <Home {...userState} />
                        </Route>
                    </MediaProvider>
                </Switch>
            </div>
        </>
    )
}

export default App

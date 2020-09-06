import React, {useContext} from 'react'
import UserContext from "../../context/user";
import UserAvatar from "../partials/UserAvatar";

const Home = () => {
    return (
        <>
            { userId > 0 ? <UserAvatar avatar={avatar} /> : ''}
        </>
    )
}

export default Home

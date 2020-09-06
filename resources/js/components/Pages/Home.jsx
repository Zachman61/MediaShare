import React, {useContext} from 'react'
import UserContext from "../../context/user";
import UserAvatar from "../partials/UserAvatar";

const Home = () => {
    const {userState: {id: userId, avatar, username}} = useContext(UserContext)
    return (
        <>
            <p>Home</p>
            { userId > 0 ? <UserAvatar avatar={avatar} /> : ''}

        </>
    )
}

export default Home

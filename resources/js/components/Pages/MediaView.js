import React, {useContext, useEffect} from 'react'
import {useParams} from 'react-router-dom'
import MediaContext from "../../context/media";
import Spinner from "../partials/Spinner";

const MediaView = () => {
    const {hash} = useParams();
    const {mediaState: {media}, dispatchForMedia} = useContext(MediaContext)
    useEffect(() => {
        axios.get(`/api/media/${hash}`)
            .then(res =>
                dispatchForMedia({
                    type: 'VIEW',
                    payload: res.data
                })
            )
            .catch(err => console.log(err))
    }, [])
    if (!Object.keys(media).length) {
        return <Spinner />
    }
    return (
        <>
            <h3>{media.title}</h3>
        </>
    )
}

export default MediaView

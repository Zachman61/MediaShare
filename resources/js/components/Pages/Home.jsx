import React, {useContext, useEffect} from 'react'
import MediaItem from "../partials/MediaItem";
import MediaContext from "../../context/media";
import Spinner from "../partials/Spinner";

const Home = () => {
    const {mediaState, dispatchForMedia} = useContext(MediaContext)
    useEffect(() => {
        axios.get('/api/media')
            .then(response => {
                dispatchForMedia({type: 'LIST', payload: response.data.data})
            })

    }, [])
    if (!mediaState.media) {
        return <Spinner />
    }

    return (
        <div className='row'>
            {mediaState.media.map((media, i) => <MediaItem media={media} key={i} />)}
        </div>
    )
}

export default Home

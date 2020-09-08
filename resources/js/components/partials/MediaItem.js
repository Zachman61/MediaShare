import React from 'react'
import {Link} from 'react-router-dom'

const MediaItem = ({media}) => {
    return (
        <div className='col col-lg-3'>
            <div className='card'>
                <Link to={`/media/${media.hash}`}><img src={media.thumbnail} className='card-img-top' /></Link>
                <div className='card-body'>
                    <span className='h2 d-block'>{media.title}</span>
                    <span className='text-muted'>{media.user.username}</span>
                </div>
            </div>
        </div>
    )
}


export default MediaItem

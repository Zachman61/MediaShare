import React from 'react'

const MediaItem = ({media}) => {
    return (
        <div className='col col-lg-3'>
            <div className='card'>
                <img src={media.thumbnail} className='card-img-top' />
                <div className='card-body'>
                    <span className='h2 d-block'>{media.title}</span>
                    <span className='text-muted'>{media.user.username}</span>
                </div>
            </div>
        </div>
    )
}


export default MediaItem

import React from 'react'

import {NavLink, Link} from 'react-router-dom'

export default function Navigation({username, avatar}) {
    return (
        <nav className='navbar navbar-expand-md navbar-light bg-light border-bottom mb-2'>
            <NavLink className="navbar-brand" to='/'>MediaShare</NavLink>
            <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#siteNav"
                    aria-controls="siteNav" aria-expanded="false" aria-label="Toggle navigation">
                <span className="navbar-toggler-icon"></span>
            </button>
            <div className="collapse navbar-collapse" id="siteNav">
                <ul className='navbar-nav ml-auto'>
                    {username ?
                        <li className="nav-item dropdown">
                            <a className="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src={avatar} width="18" height="18" className="mr-1 rounded-circle"/>
                                <span className="align-middle">{username}</span>
                            </a>
                            <div className="dropdown-menu dropdown-menu-left dropdown-menu-lg-right" aria-labelledby="navbarDropdownMenuLink">
                                <a className="dropdown-item" href="#">Settings</a>
                                <div className="dropdown-divider"/>
                                <a className='dropdown-item' href='/logout'>Logout</a>
                            </div>
                        </li>:
                        <li className='nav-item'>
                            <a className='nav-link' href='/login'>Login</a>
                        </li>
                    }

                </ul>
            </div>
        </nav>
    )
}

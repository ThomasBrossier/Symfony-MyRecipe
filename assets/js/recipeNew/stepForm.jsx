import React, {useEffect, useState} from 'react';
import {ErrorMessage, FastField, getIn} from 'formik';
import {TextField} from "@mui/material";

const StepForm = ({index, remove,...props}) => {
    return (
        <div className="d-flex flex-row align-items-start my-1">
            <div className="d-flex flex-column mx-2 flex-grow-1">
                <FastField type="number"
                       multiline
                       as={TextField}
                       helperText={<ErrorMessage className="text-danger" name={`steps.${index}`}/>}
                       error={getIn(props.errors, `steps.${index}`) &&
                           getIn(props.touched, `steps.${index}`)}
                       size="small"
                       label="Décrire l'étape"
                       name={`steps.${index}`}
                />
            </div>
            <button
                type="button"
                className="btn btn-danger"
                onClick={() => remove(index)}
            >
                X
            </button>
        </div>
    )

}
export default StepForm;

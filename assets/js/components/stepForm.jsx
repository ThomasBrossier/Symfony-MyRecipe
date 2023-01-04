import React, {useEffect, useState} from 'react';
import {ErrorMessage, Field, FieldArray, Form, Formik, getIn} from 'formik';
import {Autocomplete, InputLabel, ListSubheader, MenuItem, Select, TextField} from "@mui/material";

const StepForm = ({index, remove,...props}) => {
    return (
        <div className="d-flex flex-row align-items-start my-1">
            <div className="d-flex flex-column mx-2 w-50">
                <Field type="number"
                       multiline
                       as={TextField}
                       helperText={<ErrorMessage name={`steps.${index}`}/>}
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
